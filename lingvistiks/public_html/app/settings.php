<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class settings {
	static $log_setting = 1;
	static $main_title =  array();

	function __construct() {
		self::$main_title[] =  twig::$lang["settings_name"];
	}

	static function validation_permission() {
		try {
			v::key('alles')->assert($_SESSION);
		} catch(ValidationException $exception) {
			return false;
		}
		return true;
	}

	static function check_permission() {
		if(self::validation_permission() === false) {
			access_404();
		}
	}

	static function bild_page() {
		self::check_permission();

		$main_settings = config::bild_conf();

		// сохранение настроек
		if(isset($_REQUEST['save'])) {
			config::save_conf();
			logs::add(twig::$lang["settings_config_save"], self::$log_setting);
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=settings&status=success');
			exit;
		}
		// сохранение настроек

		foreach ($main_settings as $key => $value) {
			$main_settings[$key]['value'] = config::app($key);
		}

		// security
		$security = get_security();
		twig::assign('security', $security);
		// security

		// cron task
		$scan = scandir(BASE_DIR . "/app/cron/");
		$cron_log = BASE_DIR . "/" . config::app('app_cron_dir') ."/";
		$cron_task = [];
		foreach ($scan as $folder) {
			if ($folder === '.' or $folder === '..') continue;
			$date = file_get_contents($cron_log.$folder.".txt");
			$cron_task[$folder]['date'] = unix_to_date($date);
			$cron_task[$folder]['link'] = $folder;
		}
		twig::assign('cron_task', $cron_task);
		// cron task

		// cron module
		$modules_list = module::$modules_list;
		$cron_module = [];
		foreach ($modules_list as $tag => $value) {
			$class_name = "modules\\".$tag."Cron";

			if(
				$value['module_cron'] == 1
				&& class_exists($class_name)
				&& method_exists($class_name, 'cron')
			) {
				$date = file_get_contents($cron_log.$tag.".txt");
				$cron_module[$tag]['date'] = unix_to_date($date);
				$cron_module[$tag]['link'] = $tag;
			}
		}
		twig::assign('cron_module', $cron_module);
		// cron module

		twig::assign('api_error', twig::$lang["api_error"]);
		twig::assign('app_key_access', config::app('app_key_access'));
		twig::assign('main_settings', $main_settings);
		twig::assign('content', twig::fetch('settings.tpl'));
	}

	static function security() {
		self::check_permission();

		switch ($_REQUEST['save']) {
			case 'white':
				DB::query("
					UPDATE
					" . DB::$db_prefix . "_security
					SET
						ip = '".addslashes($_REQUEST['white_ip'])."',
						status = '".($_REQUEST['white_ip_active'] ? 1 : 0)."'
					WHERE id = '1'
				");
			break;

			case 'black':
				DB::query("
					UPDATE
					" . DB::$db_prefix . "_security
					SET
						ip = '".addslashes($_REQUEST['black_ip'])."',
						status = '".($_REQUEST['black_ip_active'] ? 1 : 0)."'
					WHERE id = '2'
				");
			break;
		}

		get_security("-1");

		logs::add(twig::$lang["settings_security_save"], self::$log_setting);
		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=settings#security');
		exit;
	}

	static function cache() {
		self::check_permission();

		// приложения
		rrmdir(BASE_DIR . "/" . config::app('app_attach_dir'));

		// сессии
		session::delete_session();

		// sql
		cache::delete_cache();

		// шаблоны
		rrmdir(BASE_DIR . "/" . config::app('app_twig_dir'));

		// кеш классов
		$classmap = BASE_DIR . "/" . config::app('app_classmap_dir') . "/";
		if ($handle = opendir($classmap)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					@unlink($classmap . $file);
				}
			}
			closedir($handle);
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=settings');
		exit;
	}
}
?>
