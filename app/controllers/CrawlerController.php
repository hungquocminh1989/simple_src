<?php 
use Behat\Mink\Mink;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
class CrawlerController extends BasicController {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}

   	public static function crawler()
	{
		$startUrl = 'https://google.com';
		$startUrl1 = 'https://facebook.com';

		// init Mink and register sessions
		$mink = new Mink(array(
		    'browser' => new Session(new ChromeDriver('http://localhost:9222', null, 'https://www.google.com')),
		    'browser1' => new Session(new ChromeDriver('http://localhost:9222', null, 'https://www.facebook.com'))
		));
		$mink->setDefaultSessionName('browser');
		
		$mink->getSession()->visit($startUrl);
		$sec = $mink->getSession();
		echo '<pre>';
		file_put_contents('abc.png', $sec->getScreenshot());
		
		$mink->setDefaultSessionName('browser1');
		$mink->getSession()->setRequestHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 7.0; SM-G930VC Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36');
		$mink->getSession()->visit($startUrl1);
		$sec = $mink->getSession();
		$sec->maximizeWindow();
		
		echo '<pre>';
		file_put_contents('abc1.png', $sec->getScreenshot());
		//print_r($mink);
		//Support_Log::Log('aaaa',$sec->getPage());
		echo '<br>';
		echo 'Stop..';
		die();
	}
}
