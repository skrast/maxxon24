<?php
@date_default_timezone_set('Europe/Moscow');
define('START_MICROTIME', microtime());
define('BASE_DIR', str_replace("\\", "/", dirname(__FILE__)));
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));

require_once(BASE_DIR . '/app/helper/init.php');

if(config::app('app_key_access') == $_REQUEST['key']) {
	$_SESSION['cron'] = 1;
	cron::bild_cron();
	unset($_SESSION['cron']);
}
?>
