<?php
class module {
	static $log_setting = 7;
	static $main_title =  array();
	static $modules = [];
	static $modules_list = [];


	function __construct() {
		self::$main_title[] =  twig::$lang["module_name"];
	}

	static function validation_permission() {
		if(!$_SESSION['alles'] && !$_SESSION['module_access'] && !$_SESSION['cron']) {
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

		$modules = module::get_modules();
		$temp = [];
		foreach ($modules as $key => $value) {
			if(in_array($key, $_SESSION['user_group_module']) || $_SESSION['alles']) {
				$temp[$key] = $value;
			}
		}
		$modules = $temp;
		twig::assign('module_list', $modules);
		twig::assign('content', twig::fetch('module.tpl'));
    }

	// обработка внутренних методов модулей
	static function mod_edit() {
		$tag = $_REQUEST['module_tag'];
		$action = $_REQUEST['module_action'];

		$name_class = "modules\\".$tag;
		if(
			isset($tag)
			&& array_key_exists($tag, module::$modules)
			&& class_exists($name_class)
			&& method_exists($name_class, $action)
			&& ((module::$modules[$tag]['module_settings'] == 1 && $action == $tag.'_start') || module::$modules[$tag]['module_allow_link'] == 1)
			&& (in_array(module::$modules[$tag]['module_tag'], $_SESSION['user_group_module']) || $_SESSION['alles'])
		) {
			new $name_class();
			self::$main_title[] = $name_class::module_info("module_name");
			$name_class::$action();
		} else {
			access_404();
		}
	}
	// обработка внутренних методов модулей


	static function get_modules() {
		global $AVE_DB;

		$modules = array();

		// Получаем из БД информацию о всех установленных модулях
		$modules_db = module::$modules;

		// Определяем директорию, где хранятся модули
		$d = dir(BASE_DIR . '/' . config::app('app_module_dir') .'/');

		// Циклически обрабатываем директории
		while (false !== ($entry = $d->read()))
		{
			if (substr($entry, 0, 1) == '.') continue;
			$module_dir = $d->path . $entry . '/';
			if (!is_dir($module_dir)) continue;

			if (!(is_file($module_dir . $entry.'.php') && @include_once($module_dir . $entry.'.php')))
			{
				// Если не удалось подключить основной файл модуля module.php - Фиксируем ошибку
				$modules['errors'][] = $entry;
				continue;
			}

			$module_class = "modules\\".$entry;
			if(!class_exists($module_class)) new $module_class;
			$module_info = $module_class::module_info();

			// Дополняем массив с данными модуля
			$check = isset($modules_db[$module_info['module_tag']]) ? true : false;

			// установленные модули
			if ($check)
			{
				$module_info = $modules_db[$module_info['module_tag']];
				$module_info['module_status']			= 1;
			}
			// неустановленные модули
			else
			{
				/* подключение языковых файлов не активных модулей */
				$path = $module_dir . "lang/".config::get_user_lang($_SESSION['user_lang']).".lang_".$entry.".php";
				include($path);
				twig::$lang = array_merge(twig::$lang, $lang);
				$module_info = $module_class::module_info();
				/* подключение языковых файлов не активных модулей */

				$module_info['module_status']			= 0;
			}
			// записываем в массив
			$modules[$module_info['module_tag']] = $module_info;
		}

		$d->Close();
		return $modules;
	}

	static function module_install() {
		if(!$_SESSION['alles'] && (!$_SESSION['module_access'] && !$_SESSION['module_install_access']) && !$_SESSION['cron']) {
			access_404();
		}

		$module_list = self::get_modules();
		$tag = $_REQUEST['module_tag'];
		$module_info = $module_list[$tag];

		if($module_info) {
			DB::query("
				DELETE FROM " . DB::$db_prefix . "_module
				WHERE module_tag = '".$module_info['module_tag']."'
			");

			DB::query("
				INSERT INTO " . DB::$db_prefix . "_module
				SET
				 	module_tag = '".$module_info['module_tag']."',
				 	module_settings = '".($module_info['module_settings'] ? 1 : 0)."',
				 	module_allow_link = '".($module_info['module_allow_link'] ? 1 : 0)."',
				 	module_cron = '".($module_info['module_cron'] ? 1 : 0)."'
			");

			self::module_sql_make("install", $module_info['module_tag']);

			// копирование переводов с учетом языковых версий сайта
			foreach (glob(BASE_DIR . '/' . config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/lang/*.lang_'.$module_info['module_tag'].'.php') as $lang_file_orig) {
				$lang = explode(".", basename($lang_file_orig));
				$lang_file_copy = BASE_DIR . '/i18n/' . $lang[0]. '/'. config::app('app_module_dir'). '/lang_'. $module_info['module_tag'].".php";

				if(is_file($lang_file_orig)) {
					chmod($lang_file_orig, 0755);
					chmod($lang_file_copy, 0755);

					copy($lang_file_orig, $lang_file_copy);
					unlink($lang_file_orig);
				}
			}
			// копирование переводов с учетом языковых версий сайта

			$upload_folder = BASE_DIR . '/' . config::app('app_upload_dir') . '/' . config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/';
			if(!is_dir($upload_folder)) {
				@mkdir($upload_folder);
				@chmod($upload_folder, 0755);
			}

			$cronfile = BASE_DIR . "/" . config::app("app_cron_dir") . "/" . $module_info['module_tag']. ".txt";
			create_file($cronfile, time());

			$name_class = "modules\\".$tag;
			if(
				class_exists($name_class)
				&& method_exists($name_class, 'install')
			) {
				$name_class::install();
			}

			logs::add(twig::$lang['module_install_log'] . ' (' . $module_info['module_tag'] . ')', self::$log_setting);

			// чистим кеш
			module::get_active_modules("-1");
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module');
			exit;
		} else {
			access_404();
		}
	}

	static function module_uninstall() {
		if(!$_SESSION['alles'] && (!$_SESSION['module_access'] && !$_SESSION['module_install_access']) && !$_SESSION['cron']) {
			access_404();
		}

		$module_list = self::get_modules();
		$tag = $_REQUEST['module_tag'];
		$module_info = $module_list[$tag];

		if($module_info) {

			DB::query("
				DELETE FROM " . DB::$db_prefix . "_module
				WHERE module_tag = '".$module_info['module_tag']."'
			");

			self::module_sql_make("uninstall", $module_info['module_tag']);

			// удаление переводов с учетом языковых версий сайта
			$lang_array = config::get_lang();
			foreach ($lang_array as $lang) {
				$lang_file_orig = BASE_DIR . '/' . config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/lang/'.$lang.'.lang_'. $module_info['module_tag'].".php";
				$lang_file_copy = BASE_DIR . '/i18n/' . $lang. '/'. config::app('app_module_dir'). '/lang_'. $module_info['module_tag'].".php";
				if(is_file($lang_file_copy)) {
					chmod($lang_file_orig, 0755);
					chmod($lang_file_copy, 0755);

					copy($lang_file_copy, $lang_file_orig);
					unlink($lang_file_copy);
				}
			}
			// удаление переводов с учетом языковых версий сайта

			$name_class = "modules\\".$tag;
			if(
				class_exists($name_class)
				&& method_exists($name_class, 'uninstall')
			) {
				$name_class::uninstall();
			}

			$upload_folder = BASE_DIR . '/' . config::app('app_upload_dir') . '/' . config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/';
			rrmdir($upload_folder);

			$cronfile = BASE_DIR . "/" . config::app("app_cron_dir") . "/" . $module_info['module_tag']. ".txt";
			if(is_file($cronfile)) {
				unlink($cronfile);
			}

			logs::add(twig::$lang['module_uninstall_log'] . ' (' . $module_info['module_tag'] . ')', self::$log_setting);

			// чистим кеш
			module::get_active_modules("-1");
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module');
			exit;
		} else {
			access_404();
		}
	}

	// обработк файла sql для установки и обновления модуля
	static function module_sql_make($action, $module_tag) {
		if(!$_SESSION['alles'] && (!$_SESSION['module_access'] && !$_SESSION['module_install_access']) && !$_SESSION['cron']) {
			access_404();
		}

		$sql_file = BASE_DIR . '/' . config::app('app_module_dir') .'/' . $module_tag . "/sql.php";
		if(is_file($sql_file)) {
			ob_start();
			include($sql_file);
			ob_get_clean();

			foreach ($sql[$action] as $sql_action) {
				DB::query($sql_action);
			}

			return true;
		}
		return false;
	}
	// обработк файла sql для установки и обновления модуля

	// информация по модулю
	static function module_info($module_id) {
		$module_info = DB::row("
			SELECT * FROM " . DB::$db_prefix . "_module
			WHERE module_id = '".$module_id."'
		");
		$module_info->module_hook = explode(",", $module_info->module_hook);
		return $module_info;
	}
	// информация по модулю

	// настрйока прав модуля
	static function module_settings() {
		$void_id = (int)$_REQUEST['void_id'];
		$void_info = self::module_info($void_id);

		if(!$void_info || !$_SESSION['alles']) {
			access_404();
		}

		if($_REQUEST['save']) {

			$save_permission = [];
			foreach ($_REQUEST['module_permission'] as $value) {
				if(array_key_exists($value, twig::$lang["module_permission"])) {
					$save_permission[] = $value;
				}
			}

			DB::query("
				UPDATE " . DB::$db_prefix . "_module
				SET
				 	module_hook = '".implode(",", $save_permission)."'
				WHERE module_id = '".$void_id."'
			");

			module::get_active_modules("-1");

			echo json_encode(array("respons"=>twig::$lang["form_save_success"], "status"=>"success"));
			exit;
		}

		twig::assign('module_permission', twig::$lang["module_permission"]);
		twig::assign('module_info', $void_info);
		$html = twig::fetch('chank/module_settings.tpl');
		echo json_encode(array("title"=>twig::$lang["module_settings"],"html"=>$html, "status"=>"success"));
		exit;
	}
	// настрйока прав модуля

	// активные модули
	static function get_active_modules($clear_cache = '') {
		$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

		$sql = DB::fetchrow("
			SELECT * FROM " . DB::$db_prefix . "_module
			ORDER BY module_tag ASC
		", $clear_cache);

		foreach ($sql as $row) {
			$row->module_hook = explode(",", $row->module_hook);
			//module::$modules[$row->module_tag] = $row;
			$name_class = "modules\\".$row->module_tag;
			module::$modules[$row->module_tag] = $name_class::module_info();

			module::$modules[$row->module_tag]['module_status'] = 1;
			module::$modules[$row->module_tag]['module_id'] = $row->module_id;
			module::$modules[$row->module_tag]['module_hook'] = $row->module_hook;

			if($row->module_settings == 1 &&
				(
					in_array($row->module_tag, $_SESSION['user_group_module']) ||
					isset($_SESSION['alles'])
				)
			) {
				module::$modules_list[$row->module_tag] = module::$modules[$row->module_tag];
			}
		}
		// Возвращаем список модулей
		//return module::$modules;
	}
	// активные модули
}

?>
