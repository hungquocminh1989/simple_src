<?php 
define('SMARTY_CACHE_LIFETIME', 300);//Seconds
define('SMARTY_LEFT_DELIMITER', '{%');
define('SMARTY_RIGHT_DELIMITER', '%}');
define('SYSTEM_DEVELOPMENT_MODE', TRUE);
define('SYSTEM_DEFAULT_TIMEZONE', 'Asia/Ho_Chi_Minh');
define('SYSTEM_ROOT_DIR', str_replace("\\", '/', getcwd()));
define('SYSTEM_TMP_DIR', SYSTEM_ROOT_DIR.'/tmp');
define('SYSTEM_DB_DIR', SYSTEM_ROOT_DIR.'/db');
define('SYSTEM_VIEW_JS', SYSTEM_ROOT_DIR.'/app/views/js');
define('SYSTEM_JS_ENCRYTION', FALSE);
define('SQL_HEADER_SCRIPT_AUTO', "/**\r\nAuto SQL Script\r\n**/");
define('SQL_HEADER_SCRIPT_MANUAL', "/**\r\nCustomze SQL Script\r\n**/");