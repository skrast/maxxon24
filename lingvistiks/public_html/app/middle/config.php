<?php

class config {
	static $app = [];
	static $default_config = [
		'URL_SUFF' => [
			'DESCR' =>'URL_SUFF',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SYS_MAIL' => [
			'DESCR' =>'SYS_MAIL',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SYS_MAIL_SENDER' => [
			'DESCR' =>'SYS_MAIL_SENDER',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SYS_NAME' => [
			'DESCR' =>'SYS_NAME',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SYS_MAIL_SIG' => [
			'DESCR' =>'SYS_MAIL_SIG',
			'default' => '',
			'TYPE' => 'text',
			'VARIANT' => ''
		],
		'SYS_PEAR_PAGE' => [
			'DESCR' =>'SYS_PEAR_PAGE',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SYS_PEAR_PAGE_LOG' => [
			'DESCR' =>'SYS_PEAR_PAGE_LOG',
			'default' => '',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'SESSION_LIFETIME' => [
			'DESCR' =>'SESSION_LIFETIME',
			'default' => 86400,
			'TYPE' => 'integer',
			'VARIANT' => ''
		],
		'COOKIE_LIFETIME' => [
			'DESCR' =>'COOKIE_LIFETIME',
			'default' => 2073600,
			'TYPE' => 'integer',
			'VARIANT' => ''
		],
		'CACHE_LIFETIME' => [
			'DESCR' =>'CACHE_LIFETIME',
			'default' => 0,
			'TYPE' => 'integer',
			'VARIANT' => ''
		],
		'CACHE_LIFETIME_LONG' => [
			'DESCR' =>'CACHE_LIFETIME_LONG',
			'default' => 0,
			'TYPE' => 'integer',
			'VARIANT' => ''
		],
		'AVATAR_WIDTH' => [
			'DESCR' =>'AVATAR_WIDTH',
			'default' => '100',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'AVATAR_HEIGHT' => [
			'DESCR' =>'AVATAR_HEIGHT',
			'default' => '100',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
		'AVATAR_QQ' => [
			'DESCR' =>'AVATAR_QQ',
			'default' => '100',
			'TYPE' => 'string',
			'VARIANT' => ''
		],
	];

	function __construct() {

	}

	static function work() {

		foreach (glob(BASE_DIR . '/config/*.config.php') as $configfile) {
			$s_config = require_once($configfile);
			config::$app = array_merge(config::$app, $s_config);
		}

		DB::work();
	}

	static function app($var='') {
		return ($var) ? config::$app[$var] : config::$app;
	}

	static function bild_conf() {
		return array_replace_recursive(self::$default_config, twig::$lang['main_config']);
	}


	static function save_conf() {
		$main_settings = config::bild_conf();

		$set='<?php
return [
';

foreach ($_REQUEST['setting'] as $k => $v) {
	switch ($main_settings[$k]['TYPE']) {
		case 'bool' : $v=$v ? 'true' : 'false'; break;
		case 'integer' : $v=intval($v); break;
		case 'string' : $v="'".add_slashes(clear_tags($v))."'";break;
		case 'text' : $v="'".add_slashes(clear_tags($v))."'";break;
		default : $v="'".add_slashes(clear_tags($v))."'";break;
	}

	$set.="
	//".$main_settings[$k]['DESCR'];
	$set.="
	'".$k."' => ".$v.",\r\n\r\n";
}
$set.='];
?>';

		file_put_contents(BASE_DIR.'/config/main.config.php',$set);
	}

	// список доступных языковых версий
	static function get_lang() {
		$skip = array('.', '..');
		$scan_folder = BASE_DIR."/i18n/";
		$folders = scandir($scan_folder);
		$folder_array = [];
		foreach($folders as $folder) {
			if(!in_array($folder, $skip) && is_dir($scan_folder . $folder)) {
				$folder_array[] = $folder;
			}
		}
		return $folder_array;
	}
	// список доступных языковых версий

	// дефольная версия для пользователя
	static function get_user_lang($lang) {
		return (in_array($lang, config::get_lang())) ? $lang : config::app('app_lang');
	}
	// дефольная версия для пользователя

	// обновление системы
	static function update() {
		if(!isset($_SESSION['alles'])) return false;
		$updaters = (glob(BASE_DIR . "/cache/update/*.update.php"));
		if ($updaters)
		{
			sort($updaters);
			foreach ($updaters as $ufile)
			{
				include($ufile);
				unlink($ufile);
				$name_update = explode(".", basename($ufile));
				@logs::add(twig::$lang['config_update'] . ' (' . basename($name_update[0]) . ')');
			}
		}
		return true;
	}
	// обновление системы
}
?>
