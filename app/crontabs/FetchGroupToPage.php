<?php 

class FetchGroupToPage extends Crontab {

	const TOKEN_PAGE = "EAASp3DPmNo8BACoVMsMJZCTD940fFza8KrV5ZBtuGADupFZBvov2Ovfbw4KAQXM9QZBwDs1YYARvElVxE70mfZANIrOxN59MSuEBF7DZBVcfLjtiS2jx0eBroOzQbmKR8fJNnA5n8CmfDWyxcZCNsafVVG9rsHgDK1eSijX5R9IBWtmRT8geZCY0";
	const TOKEN_PROFILE = "EAAAAAYsX7TsBAHmYmnMHlIcjPMb7tJyq8HzKbYUtjpJW9ZBcWjTlZAaZAiBYRwCmMR74yuD7jKyQLCh6WeINvZAm97dnEYMIua08Kj7kTVR9vu01ZAtZBRxjOmrzUZCNsQi9s5EXLuqJvJjNyt8J8TyIE3h597wswrGofqHaCNWE6d0BK3ooqvPZA5m7J4Yn7RevNRgPN3LDigi39j1VZB2FD";
	const EXTRA_PRICE = 500;
	const POST_FLG = TRUE;
	const LIMIT_POST = 50;
	
	//zalo token
	//https://oauth.zaloapp.com/developer/access_token?app_id=3859169881671723020&access_profile=true&access_friends_list=true&send_msg=true&push_feed=true&auto_follow=true
	
   	public static function cron()
	{
		echo "<pre>";
		echo 'Start crontab';
		echo '<br/>';
		
		$group_kinhdoanh = '155525341777249';
		$page_dongho = '1042724125855991';
		
		$api = new fbapi();
		
		//Get post from group
		$rs = $api->getGroupPost($group_kinhdoanh, self::LIMIT_POST, self::TOKEN_PROFILE);
		
		if($rs != NULL && isset($rs->data) == TRUE){
			$arr_check_duplicate = [];			
			foreach($rs->data as $item){
				$post_id = explode("_",$item->id)[1];
				if(isset($item->message) == FALSE){
					echo "$post_id -> Error not exist message object.\r\n";
					continue;
				}
				
				$message = $item->message;
				$permalink_url = $item->permalink_url;
				$media = $item->attachments->data[0]->subattachments->data;
				
				$id_duplicate = self::checkDuplicateContent($message, $post_id, $arr_check_duplicate);
				if($id_duplicate !== FALSE){
					echo "$post_id -> Duplicate content with $id_duplicate .\r\n";
					continue;
				}
				
				$attachments = [];
				foreach($media as $item){
					if(isset($item->media->source)){
						//$attachments[] = $item->media->source;
					}
					else{
						$attachments[] = $item->media->image->src;
					}
				}
				
				//Thay đổi giá cộng tác viên
				$pattern = "/(CTV|ctv)(.*)/";
				preg_match ( $pattern , $message, $matches);
				$matches=array_map('trim',$matches);
				$price = "Giá : Liên Hệ";
				if($matches != NULL && count($matches) > 0){
					//Replace x or X -> 0
					$price = preg_replace("/[xX]/", "0", $matches[0]);
					//Remove all non numeric characters
					$price = preg_replace("/[^0-9]/", "", $price);
					$price = "💸 GIÁ : " . number_format(round((int)$price + self::EXTRA_PRICE)) . "K";
				}
				else{
					echo "$post_id -> Error price invalid.\r\n";
					continue;
				}
				
				//Remove all link
				//$message = trim(preg_replace("/(.*\d+\.*X*x*\.*[kK])/", "", $message));
				
				//Remove all price
				$message = trim(preg_replace("/(.*\d+\.*X*x*\.*[kK])/", "", $message));
				
				//Remove all empty line
				$message = trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $message));
				
				//Append custome text
				$message = "[ Hàng Quốc Tế Cao Cấp Xách Tay ]\r\n" . $message;
				$message .= "\r\n" . $price;
				$message .= "\r\n" . "✔️ Bảo hành 1 năm.";
				$message .= "\r\n" . "✔️ 1 đổi 1 nếu không giống mẫu.";
				$message .= "\r\n" . "✔️ Hàng săn sale mới 100% bao giá thị trường.";
				$message .= "\r\n" . "✔️ Liên hệ 0902676026 hoặc inbox để xem hàng trực tiếp.";
				$message .= "\r\n" . "#DongHoNamNu #HangSanSale #DongHoChinhHang #HangXachTay #GiaSock";
				$message .= "\r\n" . "#ThiTruongGiaReVN #ShopDongHoNamNu #HopTacKinhDoanh #HangNuocNgoai #DongHoThoiTrang #$post_id";
				//var_dump($message);
				
				//Get page post
				$rs_page_post = $api->getPagePost($page_dongho, self::LIMIT_POST, self::TOKEN_PAGE);
				$arr_page_post = [];
				if($rs_page_post != NULL && isset($rs_page_post->data) == TRUE){
					foreach($rs_page_post->data as $page_item){
						$arr_page_post[] = $page_item->message;
					}
				}
				//var_dump($arr_page_post);
				//var_dump($post_id);die();
				$arr_matchs = preg_grep("/$post_id/", $arr_page_post);
				//var_dump($arr_matchs);die();
				//echo count($arr_matchs);die();
				if(count($arr_matchs) === 0){					
					//Post to page
					if(self::POST_FLG === TRUE){
						$rs = $api->createPagePost($page_dongho, $message, $attachments, self::TOKEN_PAGE);
					}
					echo "$post_id -> Posted.\r\n";
				}
				else{
					echo "$post_id -> Skipped.\r\n";
				}
			}
		}
		
		echo 'End crontab';
		die();
		return FALSE;#Stop Route
	}
	
	public static function checkDuplicateContent($msg, $post_id, &$arr){
		$isDuplicate = FALSE;
		foreach($arr as $key => $item){
			similar_text($msg,$item,$percent);
			if($percent >= 80){
				$isDuplicate = $key;
				/*var_dump($msg);
				var_dump($item);
				var_dump($percent);*/
				break;
			}
		}
		$arr[$post_id] = $msg;
		
		return $isDuplicate;
	}
	
}
