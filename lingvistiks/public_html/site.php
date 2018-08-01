<?php
@date_default_timezone_set('Europe/Moscow');
define('START_MICROTIME', microtime());
define('BASE_DIR', str_replace("\\", "/", dirname(__FILE__)));
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));

require_once(BASE_DIR . '/app/helper/init.php');

site::bild_front();
?>
