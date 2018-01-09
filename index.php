<?php
require_once 'flight/Flight.php';
require_once 'flight/autoload.php';
require_once 'app/library/Smarty/Autoloader.php';
require_once 'app/config/define.php';
require_once 'app/config/config.php';
require_once 'app/controllers/Initialize.php';

//Set timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

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

Flight::start();
