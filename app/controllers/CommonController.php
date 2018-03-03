<?php 

class CommonController extends BasicController {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}

   	public static function hello()
	{
		$arr_return = array();
		$arr_return['name'] = Flight::SampleModel()->abc();
		$arr_return['javascript_src'] = Flight::javascript_obfuscator('js/hello.js',$arr_return);
		Flight::renderSmarty('hello.html',$arr_return);
	}
}
