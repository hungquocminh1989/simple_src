<?php
require_once 'flight/Flight.php';
require_once 'flight/autoload.php';
require_once 'app/Autoloader.php';//https://github.com/Nilpo/autoloader
require_once 'app/library/Smarty/Autoloader.php';
require_once 'app/library/Mink/autoload.php';
require_once 'app/library/Goutte/autoload.php';
require_once 'app/library/HTMLParser/simple_html_dom.php';
require_once 'app/library/fbtool.php';
require_once 'app/config/define.php';
require_once 'app/config/config.php';
require_once 'app/Initialize.php';
require_once 'app/Functions.php';
require_once 'app/Routes.php';

Flight::start();