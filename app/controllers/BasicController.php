<?php
namespace app\controllers;

use Flight; 

class BasicController {

    public static function flight($page, $array = null)
    {
        Flight::render($page, $array);
        return;
    }
    
    public static function displaySmarty($page, $array = null)
    {
    	if (is_null($array) == false) {
			foreach ($array as $key => $value) {
				Flight::view()->assign($key, $value);
			}
		}
        Flight::view()->display($page.'.html');
        return;
    }
    
}
