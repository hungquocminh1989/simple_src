<?php 

class FetchGroupToPage extends Crontab {

	const API_SECRET = "c1e620fa708a1d5696fb991c1bde5662";
	const URL_API_FB = "https://api.facebook.com/restserver.php";
	
   	public static function cron()
	{
		echo "<pre>";
		echo 'Start crontab';
		echo '<br/>';
		
		/*$token = self::get_token('hungquocminh1989@gmail.com','');
		var_dump($token);*/
		
		$group_id = '155525341777249';
		$page_id = '1042724125855991';
		
		$token_page = "EAASp3DPmNo8BAIUwHL7GYqDdO4zESZBjPTtCcAvXs5tYbCPjuZBwmVZAZASa1IHI8SD0lMiX0Bb5htzAFHBL26wZA4mrIniXugRMwNqZATFbspsbWWnuw2e4630rOuOdINlZAJGoKQpxonxuwmAFyoVADfS8dZAhlqcwaJuU04oFOI45ASVMcSyG";
		$token_main = "EAAAAAYsX7TsBANQdwJVUL47ZAXXmbW5ub6svVB5WzkiAcHQXzJDz5YRAVQWClLE4HZBryFEWfyfvVIqAYLxEWfwLswbJBBqsOmKMv2ZAf4gldgfLUZCpATXbwt09sFplT2mVY7iEpVTOCZCwdUnTmsFZAz28oK7wth34y5TJYe6LswSBiNIZAaZAHHiNyJ4qVFMNeeJEUCB6PGZBF3rnVzvPL";
		
		//Get post from group
		$url = "https://graph.facebook.com/v2.10/$group_id/feed?fields=message,attachments&limit=1";
		//echo $url;die();
		$postField = array(
			'fields' => 'message,attachments',
			'limit' => 1,
			'access_token' => $token_main,
		);
		$rs = json_decode(self::cURL('GET',$url,$postField));

		$content = $rs->data[0]->message;
		$media = $rs->data[0]->attachments->data[0]->subattachments->data;
		var_dump($rs);die();
		$attachments = [];
		foreach($media as $item){
			if(isset($item->media->source)){
				$attachments[] = $item->media->source;
			}
			else{
				$attachments[] = $item->media->image->src;
			}
		}
		
		//Post to page
		$postField = array(
			'message' => $content,
			'access_token' => $token_page,
			'attached_media[]' => '{"media_fbid":"2112409342220792"}',
			//'child_attachments' => '{"picture" : "'.$attachments[0].'"}' ,
		);
		$url = "https://graph.facebook.com/v2.10/$page_id/feed";
		$rs = self::cURL('POST',$url,$postField);
		var_dump($rs);
		echo 'End crontab';
		die();
		return FALSE;#Stop Route
	}

	public static function get_token($user, $pass){
		header('Origin: https://facebook.com');
		$data = array(
			"api_key" => "3e7c78e35a76a9299309885393b02d97",
			//"credentials_type" => "password",
			"email" => $user,
			"format" => "JSON",
		//	"generate_machine_id" => "1",
		//	"generate_session_cookies" => "1",
			"locale" => "vi_vn",
			"method" => "auth.login",
			"password" => $pass,
			"return_ssl_resources" => "0",
			"v" => "1.0"
		);
		self::sign_creator($data);
		$response = self::cURL('GET', false, $data);
		$res = json_decode($response,true);
		$return_token = "";
		if(isset($res['access_token'])){
			$return_token = $res['access_token'];
		}
		return $return_token;
	}
	
	public static function sign_creator(&$data){
		$sig = "";
		foreach($data as $key => $value){
			$sig .= "$key=$value";
		}
		$sig .= self::API_SECRET;
		$sig = md5($sig);
		return $data['sig'] = $sig;
	}
	
	public static function cURL($method = 'GET', $url = false, $data = NULL){
	//sign_creator($data);
		//print_r($data);
		$c = curl_init();
		$user_agents = array(
			"Mozilla/5.0 (iPhone; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13D15 Safari Line/5.9.5",
			"Mozilla/5.0 (iPhone; CPU iPhone OS 9_0_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13A452 Safari/601.1.46 Sleipnir/4.2.2m","Mozilla/5.0 (iPhone; CPU iPhone OS 9_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13E199 Safari/601.1","Mozilla/5.0 (iPod; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) CriOS/45.0.2454.89 Mobile/13D15 Safari/600.1.4","Mozilla/5.0 (iPhone; CPU iPhone OS 9_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13E198 Safari/601.1"
		);
		$useragent = $user_agents[array_rand($user_agents)];
		$opts = array(
			CURLOPT_URL => ($url ? $url : self::URL_API_FB).($method == 'GET' ? '?'.http_build_query($data) : ''),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_USERAGENT => $useragent
		);
		if($method == 'POST'){
			$opts[CURLOPT_POST] = true;
			$opts[CURLOPT_POSTFIELDS] = $data;
		}
		curl_setopt_array($c, $opts);
		$d = curl_exec($c);
		curl_close($c);
		return $d;
	}
	
}
