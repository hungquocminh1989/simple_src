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
		
		Flight::renderSmarty(__FUNCTION__,$arr_return);
	}
    
}
