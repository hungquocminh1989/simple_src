<?php 

class CommonController extends BasicController {

	public static function index()
	{
		/*$model = new Models\SampleModel();
		$tmp = $model->getTable();*/
		
	    Flight::renderSmarty(__FUNCTION__);
	}

   	public static function hello()
	{
		//$model = new Models\SampleModel();
		/*$tmp = $model->getTable();*/
		/*$model = Flight::SampleModel();
		$tmp = $model->getTable();*/
		$arr_return = array();
		$arr_return['name'] = 'Hello user.';
		
		Flight::renderSmarty(__FUNCTION__,$arr_return);
	}
    
}
