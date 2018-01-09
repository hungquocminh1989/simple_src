<?php
session_start();

//Set timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

//Register Folder Autoload
Flight::path(getcwd().'/app/models');
Flight::path(getcwd().'/app/controllers');

//Register Class Autoload
Flight::register('CommonController', 'CommonController');
Flight::register('SampleModel', 'SampleModel');

//Register Smarty
Smarty_Autoloader::register();

//Register to Flight
Flight::register('view', 'Smarty', array(), function($smarty){
	$smarty->left_delimiter = SMARTY_LEFT_DELIMITER;
	$smarty->right_delimiter = SMARTY_RIGHT_DELIMITER;
    $smarty->template_dir = './app/views/';
    $smarty->compile_dir = './cache/';
    $smarty->cache_dir = './cache/';
    //$smarty->config_dir = './config/';
    $smarty->escape_html = TRUE;
});

//Override Flight's default render method
Flight::map('render', function($template, $data){
    if (is_null($data) == false) {
		Flight::view()->assign($data);
	}
    Flight::view()->display($template.'.html');
});

//Override Flight's default error method
/*Flight::map('error', function(Exception $ex){
    // Handle error
    $request = Flight::request();
    echo '<pre>';
    var_dump($ex->getTraceAsString());
    var_dump($request);
});*/

//Set path folder template
Flight::set('flight.views.path', 'app/views/');

// Initialize Controller
$controller = Flight::CommonController();
Flight::route('/(index)', array($controller, 'index'));
Flight::route('/hello', array($controller, 'hello'));

// Membership Controller
/*$membership = new MembershipController();
Flight::route('GET /login', array($membership, 'login'));
Flight::route('POST /login', array($membership, 'loginAttempt'));
Flight::route('/logout', array($membership, 'logout'));
Flight::route('/profile/@name', array($membership, 'profile'));
Flight::route('GET /profile/@name/edit', array($membership, 'profileEdit'));
Flight::route('POST /profile/@name/edit', array($membership, 'profileEditAttempt'));
Flight::route('GET /sign-up', array($membership, 'register'));
Flight::route('POST /sign-up', array($membership, 'registerAttempt'));*/

// catch everything
Flight::route('/*', array($controller, 'redirect'));

