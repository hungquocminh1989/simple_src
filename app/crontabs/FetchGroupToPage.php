<?php 

class FetchGroupToPage extends Crontab {

	const TOKEN_PAGE = "EAASp3DPmNo8BAIUwHL7GYqDdO4zESZBjPTtCcAvXs5tYbCPjuZBwmVZAZASa1IHI8SD0lMiX0Bb5htzAFHBL26wZA4mrIniXugRMwNqZATFbspsbWWnuw2e4630rOuOdINlZAJGoKQpxonxuwmAFyoVADfS8dZAhlqcwaJuU04oFOI45ASVMcSyG";
	const TOKEN_PROFILE = "EAAAAAYsX7TsBAHmYmnMHlIcjPMb7tJyq8HzKbYUtjpJW9ZBcWjTlZAaZAiBYRwCmMR74yuD7jKyQLCh6WeINvZAm97dnEYMIua08Kj7kTVR9vu01ZAtZBRxjOmrzUZCNsQi9s5EXLuqJvJjNyt8J8TyIE3h597wswrGofqHaCNWE6d0BK3ooqvPZA5m7J4Yn7RevNRgPN3LDigi39j1VZB2FD";
	
   	public static function cron()
	{
		echo "<pre>";
		echo 'Start crontab';
		echo '<br/>';
		
		$group_kinhdoanh = '155525341777249';
		$page_dongho = '1042724125855991';
		
		$api = new fbapi();
		
		//Get post from group
		$rs = $api->getGroupPost($group_kinhdoanh,self::TOKEN_PROFILE);
		var_dump($rs);
		die();
		if($rs != NULL && isset($rs->data) == TRUE){
			foreach($rs->data as $item){
				$post_id = explode("_",$item->id)[1];
				
				$message = $item->message;
				$permalink_url = $item->permalink_url;
				//var_dump($post_id);
				//var_dump($item);
				//die();
				$media = $item->attachments->data[0]->subattachments->data;
				
				$attachments = [];
				foreach($media as $item){
					if(isset($item->media->source)){
						//$attachments[] = $item->media->source;
					}
					else{
						$attachments[] = $item->media->image->src;
					}
				}
				//var_dump($attachments);die();
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
					$price = "Giá : " . number_format(round((int)$price + 300)) . "K";
				}
				
				//Remove all price
				$message = trim(preg_replace("/(.*\d+\.*X*x*\.*[kK])/", "", $message));
				
				//Remove all empty line
				$message = trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $message));
				
				//Append custome text
				$message = "[ Hàng Quốc Tế Cao Cấp Xách Tay ]\r\n" . $message;
				$message .= "\r\n" . "✔️ Bảo hành 1 năm.";
				$message .= "\r\n" . "✔️ 1 đổi 1 nếu không giống mẫu.";
				$message .= "\r\n" . "✔️ Hàng săn sale mới 100% bao giá thị trường.";
				$message .= "\r\n" . "✔️ Liên hệ đặt lịch để xem hàng trực tiếp.";
				$message .= "\r\n" . $price;
				$message .= "\r\n" . "#DongHoNamNu #HangSanSale #DongHoChinhHang #$post_id";
				//var_dump($message);
				
				//Post to page
				$rs = $api->createPagePost($page_dongho, $message, $attachments, self::TOKEN_PAGE);
			}
		}
		
		
		//var_dump($rs);
		echo 'End crontab';
		die();
		return FALSE;#Stop Route
	}
	
}
