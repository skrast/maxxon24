<?php

setlocale (LC_ALL, "ru_RU.UTF-8");
if (!defined('BASE_DIR')) exit;

// запуск контроллера ошибок
ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);
include(BASE_DIR . '/vendor/whoops/vendor/autoload.php');
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// запуск контроллера ошибок

/**
 * Слешевание (для глобальных массивов)
 * рекурсивно обрабатывает вложенные массивы
 *
 * @param array $array обрабатываемый массив
 * @return array обработанный массив
 */
function add_slashes($array)
{
	if(is_array($array)) {
		reset($array);
		while (list($key, $val) = each($array))
		{
			if (is_string($val))	$array[$key] = addslashes($val);
			elseif (is_array($val))	$array[$key] = add_slashes($val);
		}
	}

	return $array;
}

if (!get_magic_quotes_gpc())
{
	$_GET     = add_slashes($_GET);
	$_POST    = add_slashes($_POST);
	$_REQUEST = array_merge($_POST, $_GET);
	$_COOKIE  = add_slashes($_COOKIE);
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']))
	{
		if ('on' == strtolower($_SERVER['HTTPS'])) return true;
		if ('1' == $_SERVER['HTTPS']) return true;
	}
	elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT']))
	{
		return true;
	}

	return false;
}

function set_host()
{
	if (isset($_SERVER['HTTP_HOST']))
	{
		// Все символы $_SERVER['HTTP_HOST'] приводим к строчным и проверяем
		// на наличие запрещённых символов в соответствии с RFC 952 и RFC 2181.
		$_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
		if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST']))
		{
			// $_SERVER['HTTP_HOST'] не соответствует спецификациям.
			// Возможно попытка взлома, даём отлуп статусом 400.
			header('HTTP/1.1 400 Bad Request');
			exit;
		}
	}
	else
	{
		$_SERVER['HTTP_HOST'] = '';
	}

	$ssl = is_ssl();
	$shema = ($ssl) ? 'https://' : 'http://';
	$host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
	$port = ($_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' || $ssl) ? '' : ':' . $_SERVER['SERVER_PORT'];

	$abs_path = dirname((!strstr($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) && (@php_sapi_name() == 'cgi')) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	if (defined('ACP')) $abs_path = dirname($abs_path);
	define('ABS_PATH', rtrim(str_replace("\\", "/", $abs_path), '/') . '/');
	define('ABS_PATH_ADMIN_LINK', rtrim(str_replace("\\", "/", $abs_path), '/') . '/writer/');
	define('HOST', $shema . $host . $port . ABS_PATH);
	define('HOST_NAME', $shema . $host . $port);
}
set_host();

// func
require_once(BASE_DIR . '/app/helper/func.php');
// func

// composer loader
require_once BASE_DIR.'/vendor/loader/vendor/autoload.php';
require_once BASE_DIR.'/vendor/validation/vendor/autoload.php';
// composer loader

// loader
$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(BASE_DIR . '/app/middle');
$loader->addDirectory(BASE_DIR . '/app/site');
$loader->addDirectory(BASE_DIR . '/app');
$loader->addDirectory(BASE_DIR . '/modules');
$loader->setTempDirectory(BASE_DIR . '/cache/classmap/');
$loader->excludeDirectory(BASE_DIR . '/app/helper');
$loader->excludeDirectory(BASE_DIR . '/app/sql');
$loader->register(); // Run the RobotLoader
// основные компоненты системы
// loader

backend::prepare_base();

// профайдер
/*
if(config::app('app_profiling')) {
	new errorvisor();
}
*/
// профайдер

mb_internal_encoding(config::app("app_charset"));

// session
session::work();
// session

// проверка сессии пользователя
if (!auth::auth_sessions())
{
	if (!auth::auth_cookie())
	{
		// чистим данные авторизации в сессии
		unset($_SESSION['user_id'], $_SESSION['user_password'], $_SESSION['user_group']);
		// чистим данные авторизации в сессии
	}
}

if($_SESSION['user_id']) {
	DB::query("
		UPDATE " . DB::$db_prefix . "_users
		SET
			user_visittime = '" . time() . "',
			user_ip    =  INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
		WHERE
			id = '" . $_SESSION['user_id'] . "'
	");
}

auth::check_user_ban();
// проверка сессии пользователя

// загрузка основных переменных в шаблоны
twig::bild_twig();
// загрузка основных переменных в шаблоны

// Устанавливаем обновления системы
config::update();
// Устанавливаем обновления системы

// запуск CSRF validator
if(config::app('app_csrf_validator') && !defined('STOP_CSRF')) {
	require_once(BASE_DIR . '/vendor/CSRF/vendor/autoload.php');
	$csrf = new \Riimu\Kit\CSRF\CSRFHandler();

	try {
	    $csrf->validateRequest(true);
	} catch (\Riimu\Kit\CSRF\InvalidCSRFTokenException $ex) {
	    header('HTTP/1.0 400 Bad Request');
	    exit('Bad CSRF Token!');
	}

	$token = $csrf->getToken();
	twig::assign('csrf_token', $token);
}
// запуск CSRF validator
?>
