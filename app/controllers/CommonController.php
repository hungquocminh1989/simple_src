<?php 

class CommonController extends BasicController {

	public static function index()
	{
	    Flight::renderSmarty(__FUNCTION__);
	}

   	public static function hello()
	{
		$arr_return = array();
		$sample = new SampleModel();
		$arr_return['name'] = $sample->abc();
		$arr_return['javascript_src'] = Flight::javascript_obfuscator('js/hello.js',$arr_return);
		Flight::renderSmarty('hello.html',$arr_return);
		return FALSE;#Stop Route
	}
}
