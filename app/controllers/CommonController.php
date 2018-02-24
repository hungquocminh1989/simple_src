<?php 

class CommonController extends BasicController {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}

   	public static function hello()
	{
		Flight::Util()->var_dump($_SESSION);
		$arr_return = array();
		$arr_return['name'] = Flight::SampleModel()->abc();
		$arr_return['javascript_src'] = Flight::obfuscator_javascript('js/hello.js',$arr_return);
		Flight::renderSmarty('hello.html',$arr_return);
	}
	
	public static function action_obfuscator()
	{
		$arr_return = array();
		$arr_return['file'] = $_GET['file'];
		Flight::renderSmarty('obfuscator.html',$arr_return);
	}
    
}
