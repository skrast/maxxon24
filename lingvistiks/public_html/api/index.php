<?php

if ($_SERVER['REDIRECT_PATH_INFO']) $_SERVER['PATH_INFO'] = $_SERVER['REDIRECT_PATH_INFO'];
define('BASE_DIR1', str_replace("\\", "/", dirname(__FILE__)));
@date_default_timezone_set('Europe/Moscow');
define('START_MICROTIME', microtime());
define('STOP_CSRF', 1);

// fix path
define('BASE_DIR', str_replace("/api", "", BASE_DIR1));

// класс с общим наборов функций
require_once(BASE_DIR . '/app/helper/init.php');
define('ABS_PATH_FIX', str_replace("/api", "", ABS_PATH));
// класс с общим наборов функций

// почтовый трекер
if($_REQUEST['mail_id'] && $_REQUEST['hash']) {
	sendmail::tracker();
}
// почтовый трекер

module::get_active_modules();

// RESTful
require (BASE_DIR.'/vendor/RESTful/vendor/autoload.php');
require (BASE_DIR.'/api/myRestApi/Api.php');
require (BASE_DIR.'/api/myRestApi/ApiError.php');
use RestService\Server;

Server::create('/', 'myRestApi\Api')
        ->addPutRoute('module/([a-z]+)', 'module')
        ->addGetRoute('module/([a-z]+)', 'module')
        ->addPostRoute('module/([a-z]+)', 'module')
->run();
// RESTful
?>
