<?php 
use Behat\Mink\Mink;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
class CrawlerController extends BasicController {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}
	
	public static function crawler_goutte()
	{
		$driver = new \Behat\Mink\Driver\GoutteDriver();
		$client = new \Goutte\Client();
		// Do more configuration for the Goutte client

		$driver = new \Behat\Mink\Driver\GoutteDriver($client);
		$startUrl = 'https://facebook.com';
		$mink = new Mink(array(
		    'browser' => new Session($driver)
		));
		$mink->setDefaultSessionName('browser');
		$mink->getSession()->setRequestHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 7.0; SM-G930VC Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36');
		$mink->getSession()->visit($startUrl);
		$sec = $mink->getSession();
		$control = $mink->assertSession();
		$control->fieldExists('email')->setValue('hungquocminh1989@gmail.com');
		$control->fieldExists('pass')->setValue('@QuocMinh5510453');
		$control->elementExists('xpath','//*[@name="login"]')->click();
		
		echo '<pre>';
		file_put_contents('abc.html', $sec->getPage()->getHtml());
		
		echo '<br>';
		echo 'Stop..';
		die();
	}

   	public static function crawler_mink()
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
		$control = $mink->assertSession();
		$control->fieldExists('email')->setValue('hungquocminh1989@gmail.com');
		$control->fieldExists('pass')->setValue('@QuocMinh5510453');
		$control->elementExists('xpath','//*[@name="login"]')->click();
		$sec->wait(10000,"document.readyState === 'complete'");
		file_put_contents('abc.html', $sec->getPage()->getHtml());
		echo '<pre>';
		file_put_contents('abc1.png', $sec->getScreenshot());
		//print_r($mink);
		//Support_Log::Log('aaaa',$sec->getPage());
		echo '<br>';
		echo 'Stop..';
		die();
	}
}
