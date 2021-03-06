<?php 
use Behat\Mink\Mink;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;

set_time_limit(0);
class HtmlGeneratorController extends Controller {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}
	public function save_file($url_source, $path_save){
		try{
			$contents = file_get_contents($url_source);
			Support_File::CreateFolder($path_save);
			file_put_contents($path_save, $contents);
			/*echo "Download:$url_source<br>";*/
			return TRUE;
			
		} catch (Exception $ex) {
			echo "Error download:$url_source<br>";
			return FALSE;
		}
	}
	
	public function get_folder_path($url){
		
		$info = pathinfo($url);
		if(isset($info["extension"]) == TRUE && $info["extension"] != ""){
			$arr = explode("/", $url);
			unset($arr[count($arr)-1]);
			$url = implode("/",$arr);
		}
		
		return $url;
	}
	
	public function browser_download($url_source, $path_save){
		try{
			//Chrome driver
			$mink = new Mink(array(
			    'browser' => new Session(new ChromeDriver('http://localhost:9222', null, 'https://www.google.com'))
			));
			
			$mink->setDefaultSessionName('browser');
			
			//access with chrome
			$mink->getSession()->setRequestHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
			
			$mink->getSession()->visit($url_source);
			$sec = $mink->getSession();
			//$sec->wait(10000);
			
			//Tạo file html tạm
			$tmp_file = SYSTEM_TMP_DIR.'/tmp_download_'.Date('YmdHis').'.html';
			file_put_contents($tmp_file, $sec->getPage()->getHtml());
			
			//Lấy nội dung trong thẻ <pre>
			$html = file_get_html($tmp_file);
			foreach($html->find('pre') as $element) 
			{
				file_put_contents($path_save,$element->text());
			}
			
			//Xóa file html tạm
			Support_File::DeleteFile($tmp_file);
			echo "Complete browser_download:$url_source<br>";
			return TRUE;
		} catch (Exception $e) {
			echo "Error browser_download:$url_source<br>";
			return FALSE;
		}
		
	}
	
	
	public function browser_crawler($url, $tmp_path){
		//Start commandline
		//chrome.exe --remote-debugging-address=127.0.0.1 --remote-debugging-port=9222
		//chrome.exe --disable-gpu --headless --remote-debugging-address=127.0.0.1 --remote-debugging-port=9222
		
		//Goutte driver
		/*$client = new \Goutte\Client();
		$driver = new \Behat\Mink\Driver\GoutteDriver($client);
		$mink = new Mink(array(
		    'browser' => new Session($driver)
		));*/
		
		//Chrome driver
		$mink = new Mink(array(
		    'browser' => new Session(new ChromeDriver('http://localhost:9222', null, 'https://www.google.com'))
		));
		
		$mink->setDefaultSessionName('browser');
		
		//access with chrome
		$mink->getSession()->setRequestHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
		
		$mink->getSession()->visit($url);
		$sec = $mink->getSession();
		//$sec->wait(30000);
		
		file_put_contents($tmp_path, $sec->getPage()->getHtml());
		
		return $tmp_path;
	}
	
	public function format_css_before_search($css){
		//replace url cho xuống dòng
		$css = preg_replace('/(url\()/', "\r\n"."url(", $css);
		$css = preg_replace('/(format\()/', "\r\n"."format(", $css);
		
		return $css;
	}
	
	public function check_file_css($url_css, $path_css, $url_root, $path_root){
		
		$url_folder = self::get_folder_path($url_css);
		$path_folder = self::get_folder_path($path_css);
		
		$arr_list = array();
		$contents = file_get_contents($url_css);
		
		$contents = self::format_css_before_search($contents);
		
		preg_match_all('/(url)(\()(.*)(\))/', $contents, $matches);	
		if($matches != NULL && count($matches) > 0 && count($matches[3]) > 0){
			foreach($matches[3] as $key => $value){
				$value = str_replace('"','',$value);
				$value = str_replace("'",'',$value);
				$arr_list[] = $value;
			}
			if($arr_list != NULL && count($arr_list) > 0){
				foreach($arr_list as $file){
					//
					$arr_path = explode("/", $path_folder);
					$arr_url = explode("/", $url_folder);
					
					if(strpos($file, "../") !== FALSE){
						//Co ton tai back folder
						preg_match_all('/(\.\.\/)/', $file, $countFolder);	
						if($countFolder != NULL && count($countFolder) > 0 && count($countFolder[0]) > 0){
							
							//back folder
							for($i = 0; $i < count($countFolder[0]) ; $i++){
								unset($arr_path[count($arr_path)-1]);
								unset($arr_url[count($arr_url)-1]);
							}
							
							//remove all ../
							$file = preg_replace('/(\.\.\/)/', '', $file);
							
							$arr_path[] = $file;
							$arr_url[] = $file;
							
							//Set path
							$path_folder_back = implode("/",$arr_path);
							$url_folder_back = implode("/",$arr_url);
							
							$status = self::save_file($url_folder_back, $path_folder_back);
							/*if($status === TRUE){
								echo "Check OK:".$url_folder_back .'<br>';
							}*/
						}
					}
					else{
						//Khong ton tai back folder
						$arr_path[]= $file;
						$arr_url[]= $file;
						
						//Set path
						$path_folder_normal = implode("/",$arr_path);
						$url_folder_normal = implode("/",$arr_url);
						
						$status = self::save_file($url_folder_normal, $path_folder_normal);
						/*if($status === TRUE){
							echo "Check OK:".$url_folder .'<br>';
						}*/
					}
				}
				
			}
		}
		
		//Download fonts/
		/*preg_match_all('/(fonts.*\.woff[0-9]*)/', $contents, $matches);	
		if($matches != NULL && count($matches) > 0 && count($matches[0]) > 0){
			foreach($matches[0] as $key => $value){
				$url_fonts = $url_root . $value;
				$path_fonts = $path_root . '/' . $value;
				$status = self::save_file($url_fonts, $path_fonts);
			}
		}*/
		
	}
	
	public function replace_dynamic_link($link){
		$lnk_return = $link;
		$regex_windows_file = '/(\\\|\/|\:|\*|\?|\"|\<|\>|\|)/';//Các kí tự cấm trong windows
		
		if(strpos($link,'?') !== FALSE){//Có tồn tại kí tự ?
			$arr_explode = explode('?',$link);
			if($arr_explode != NULL && count($arr_explode) > 0){
				$filename = $arr_explode[count($arr_explode)-2];
				$arr_explode_file = explode('.',$filename);
				if($arr_explode_file != NULL && count($arr_explode_file) > 0){
					$arr_explode_file[count($arr_explode_file)-2] = $arr_explode_file[count($arr_explode_file)-2] . '_' . Date('YmdHis');;
					$filename = implode('.',$arr_explode_file);
				}
				$arr_explode[count($arr_explode)-2] = $filename;
				unset($arr_explode[count($arr_explode)-1]);
				$lnk_return = implode('?',$arr_explode);
			}
			
			//
			
			
			return $lnk_return;
		}
		return FALSE;
		
	}
	
	public function check_content_css($css){
		$css = self::format_css_before_search($css);
		preg_match_all('/(fonts.*\.woff[0-9]*)/', $css, $matches);	
		if($matches != NULL && count($matches) > 0 && count($matches[0]) > 0){
			foreach($matches[0] as $key => $value){
				$url_fonts = $url_root . $value;
				$path_fonts = $path_root . '/' . $value;
				$status = self::save_file($url_fonts, $path_fonts);
			}
		}
		
	}

   	public static function downloadTemplate()
	{
		/*self::browser_download('https://www.google.com.vn/xjs/_/js/k=xjs.s.vi.jT1lAK2CGMU.O/am=IIuwH_8Akv9fGFAUbAUjLDCDgmET/rt=j/d=1/exm=sx,sb,cdos,elog,hsm,jsa,r,d,csi/ed=1/dg=0/rs=ACT90oHocMSiOrr07tNOVPfRqwqzvIyLOQ/m=aa,abd,async,dvl,foot,fpe,ipv6,lu,m,mpck,mu,sf,sonic,spch,tl,vs,d3l,tnv,exdp,udlg,me,atn,aam1T?xjs=s1','abc.js');
		echo 111;
		die();*/
		/*$a = "
			url(../img/offers/special-offer-bg.png) repeat;
	Line 13671:   background: url(../img/forms/paypal-label.png) no-repeat;
	background: url(loading-2.gif) no-repeat center white;
		";
		preg_match_all('/(url)(\()(.*)(\))/', $a, $matches);	
		var_dump($matches);
		die();*/
		$arr_return = array();
		
		//Define key search
		$arr_tags = array();
		$arr_tags['link'] = 'href';
		$arr_tags['script'] = 'src';
		$arr_tags['img'] = 'src';
		$arr_tags['style'] = NULL;
		
		//Thông Tin Site Nguồn
		$folder_src = SYSTEM_TMP_DIR.'/download_template';
		$url = 'http://template.8guild.com/bushido/v1.4/shop-single-item-v1.html';
		$file_name = 'index.html';
		
		//Start crawler
		Support_File::CreateFolder($folder_src);
		$path_crawler = $folder_src . '/tmp_crawler.html';
		self::browser_crawler($url, $path_crawler);
		
		// Create DOM from URL or file
		$html = file_get_html($path_crawler);
		
		if($html != NULL && $html != ''){
			$url = self::get_folder_path($url);
			$path_file = $folder_src . "/$file_name";
			
			foreach($arr_tags as $key => $value){
				foreach($html->find($key) as $element) 
				{
					
					if($key == 'style'){
						
						//Xử lý check download các css nằm trong page Html
						$css = $element->text();
						if($css != NULL && $css != ''){
							
						}
						
					}
					else{
						if(isset($element->$value) === TRUE){
						
							$url_save = $element->$value;
							
							//if(strpos($url_save, "../") !== FALSE){
								
							//}
							//else{
								if(strpos($url_save, $url) !== FALSE || strpos($url_save, 'http') === FALSE){
									
									//Xóa domain
									$url_removed = str_replace($url,"",$url_save);
									
									//Check và xử lý link dynamic
									$url_dynamic = self::replace_dynamic_link($url_removed);
									
									if($url_dynamic === FALSE){
										
										//Download File normal
										$path_save = $folder_src . '/' . $url_removed;
										$url_source = $url . '/' . $url_removed;
										$status_download = self::save_file($url_source, $path_save);
										if($status_download === TRUE){
											$element->$value = $url_removed;
										}
										if(strpos($path_save, '.css') !== FALSE && $status_download === TRUE){
											self::check_file_css($url_source, $path_save, $url, $folder_src);
										}
										
									}
									else{
										
										//Download File dynamic
										$path_save = $folder_src . '/' . $url_dynamic;
										$url_source = $url . '/' . $url_removed;
										$status_download = self::browser_download($url_source, $path_save);
										if($status_download === TRUE){
											$element->$value = $url_dynamic;
										}
										if(strpos($path_save, '.css') !== FALSE && $status_download === TRUE){
											self::check_file_css($url_source, $path_save, $url, $folder_src);
										}
										
									}
									
								}
								else{
									
									//link có domain khác
									//...
									
								}
								
								
							//}
							
						}
						else{
							echo  "Element '$key' Attribute '$value' Not Exits...<br>";
						}
					}
					
				}
			    
			}			
			
			$html->save($path_file);
			
		}
		
		if(Support_File::FileExists($path_crawler) === TRUE){
			Support_File::DeleteFile($path_crawler);
		}
		
		echo 'stop';
		die();
		
		Flight::renderSmarty('hello.html',$arr_return);
		return FALSE;#Stop Route
	}
}
