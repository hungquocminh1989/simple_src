<?php 

class FetchGroupToPage extends Crontab {

	const API_SECRET = "c1e620fa708a1d5696fb991c1bde5662";
	const URL_API_FB = "https://api.facebook.com/restserver.php";
	
   	public static function cron()
	{
		echo "<pre>";
		echo 'Start crontab';
		echo '<br/>';
		
		$group_kinhdoanh = '155525341777249';
		$page_dongho = '1042724125855991';
		
		$token_page = "EAASp3DPmNo8BAIUwHL7GYqDdO4zESZBjPTtCcAvXs5tYbCPjuZBwmVZAZASa1IHI8SD0lMiX0Bb5htzAFHBL26wZA4mrIniXugRMwNqZATFbspsbWWnuw2e4630rOuOdINlZAJGoKQpxonxuwmAFyoVADfS8dZAhlqcwaJuU04oFOI45ASVMcSyG";
		$token_main = "EAAAAAYsX7TsBANwPxUo9FBeSPahx1VjgCZBDN7cDzJZAiclZAO1wPZCtGpziAxTHB3JpJKrt1mcyXvrGZCJieXSdCHZCZANGpqVRXdarYFQGJBtPezVGypxFOv1hZBCjI2LyPhGAfg0DfpPuQbICg5grZA1QGE0PIMKNbSvqIZAERvqSPIw6K7yte58xtm7ZA5F8WdZASDMX6gsQJbDGnEvof6b6";
		
		$api = new fbapi();
		
		//Get post from group
		$rs = $api->getGroupPost($group_kinhdoanh,$token_main);
		
		var_dump($rs);die();
		$message = $rs->data[0]->message;
		$media = $rs->data[0]->attachments->data[0]->subattachments->data;
		
		$attachments = [];
		foreach($media as $item){
			if(isset($item->media->source)){
				//$attachments[] = $item->media->source;
			}
			else{
				$attachments[] = $item->media->image->src;
			}
		}
		var_dump($attachments);die();
		//Thay đổi giá cộng tác viên
		$pattern = "/(CTV|ctv)(.*)/";
		preg_match ( $pattern , $message, $matches);
		$matches=array_map('trim',$matches);
		$price = "Giá : Liên Hệ";
		if($matches != NULL && count($matches) > 0){
			$price = preg_replace("/[^0-9]/", "", $matches[0]);//Remove all non numeric characters
			$price = "Giá : " . number_format((int)$price + 300);
		}
		
		//(.*)(SALE|sale|Sale)(.*\d+.*[kK])
		//(.*\d+\.*X*x*\.*[kK])
		$message = trim(preg_replace("/(.*\d+\.*X*x*\.*[kK])/", "", $message));//Remove all price
		$message .= "\r\n" . $price;
		//var_dump($message);die();
		
		//Post to page
		$rs = $api->createPagePost($page_dongho, $message, $attachments, $token_page);
		var_dump($rs);
		echo 'End crontab';
		die();
		return FALSE;#Stop Route
	}
	
}
