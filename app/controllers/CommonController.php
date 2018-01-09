<?php
namespace app\controllers;

use app\models as Models; 
use Flight; 

class CommonController extends BasicController {

	public static function index()
	{
		/*$model = new Models\SampleModel();
		$tmp = $model->getTable();*/
		
		$arr_return = array();
		$arr_return['test'] = '222111';
	    Flight::render(__FUNCTION__,$arr_return);
	    return;
	}

   	public static function hello()
	{
		//$model = new Models\SampleModel();
		/*$tmp = $model->getTable();*/
		//$a = Flight::SampleModel();
		$arr_return = array();
		$arr_return['name'] = '222';
		Flight::render(__FUNCTION__,$arr_return);
	    return;
	}
    
}
