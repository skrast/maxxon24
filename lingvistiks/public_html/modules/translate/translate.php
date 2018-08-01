<?php
namespace modules;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class translate {
	static $mod_folder = "";
	static $main_title =  array();

	function __construct() {
		self::$main_title[] =  \twig::$lang["translate_name"];
	}

	static function module_info($custom='') {
		$module['module_name'] = \twig::$lang["translate_name"];
		$module['module_name_short'] = \twig::$lang["translate_name_short"];
		$module['module_tag'] = "translate";
		$module['module_version'] = "1.0";
		$module['module_desc'] = \twig::$lang["translate_description"];
		$module['module_author'] = "Максим Медведев";
		$module['module_copy'] = "&copy; 2017 Реймакс";
		$module['module_settings'] = 1;
		$module['module_allow_link'] = 1;

		self::$mod_folder = '/' . $module['module_tag'] . "/templates/";
		return ($custom!='') ? $module[$custom] : $module;
	}

	static function translate_start() {

		// доступные языковые версии
		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);
		// доступные языковые версии

		if(isset($_REQUEST['lang']) && in_array($_REQUEST['lang'], $lang_array)) {

		 	$lang_folder = BASE_DIR . '/i18n/'.$_REQUEST['lang'] . "/";

			$lang_var_temp = glob($lang_folder .'*.php');
			$lang_var_module_temp = glob($lang_folder . \config::app('app_module_dir'). '/lang_*.php');
			$lang_var_notif_temp = glob($lang_folder . '/mail/*.tpl');
			$lang_var_site_temp = glob($lang_folder . '/site/*.php');

			$lang_var = [];
			foreach ($lang_var_temp as $row) {
				$row = explode("/", $row);
				$lang_var[] = explode(".", end($row))[0];
			}

			$lang_var_module = [];
			foreach ($lang_var_module_temp as $row) {
				$row = explode("/", $row);
				$lang_var_module[] = explode(".", end($row))[0];
			}

			$lang_var_notif = [];
			foreach ($lang_var_notif_temp as $row) {
				$row = explode("/", $row);
				$lang_var_notif[] = explode(".", end($row))[0];
			}

			$lang_var_site = [];
			foreach ($lang_var_site_temp as $row) {
				$row = explode("/", $row);
				$lang_var_site[] = explode(".", end($row))[0];
			}

			if(isset($_REQUEST['var'])) {
				switch ($_REQUEST['type']) {
					case 'module':
						$lang_file = $lang_folder . \config::app('app_module_dir') . "/" .  $_REQUEST['var'] . ".php";

						if(!is_file($lang_file)) {
							access_404();
						}
					break;

					case 'notif':
						$lang_file = $lang_folder . "/mail/" .  $_REQUEST['var'] . ".tpl";

						if(!is_file($lang_file)) {
							access_404();
						}
					break;

					case 'site':
						$lang_file = $lang_folder . "/site/" .  $_REQUEST['var'] . ".php";

						if(!is_file($lang_file)) {
							access_404();
						}
					break;

					default:
						$lang_file = $lang_folder .  $_REQUEST['var'] . ".php";

						if(!is_file($lang_file)) {
							access_404();
						}
					break;
				}

				$lang_sourse = file_get_contents($lang_file);
				\twig::assign('lang_sourse', $lang_sourse);
				\twig::assign('type', $_REQUEST['type']);
				\twig::assign('var', $_REQUEST['var']);
			}

			\twig::assign('lang_var', $lang_var);
			\twig::assign('lang_var_module', $lang_var_module);
			\twig::assign('lang_var_notif', $lang_var_notif);
			\twig::assign('lang_var_site', $lang_var_site);
			\twig::assign('lang_default', $_REQUEST['lang']);
		}

		\twig::assign('content', \twig::fetch(self::$mod_folder.'translate_start.tpl'));
	}

	static function translate_work() {
		$lang_sourse = $_REQUEST['lang_sourse'];
		$lang_folder = BASE_DIR . '/i18n/'.$_REQUEST['lang'];

		if(isset($_REQUEST['var'])) {
			switch ($_REQUEST['type']) {
				case 'module':
					$lang_file = $lang_folder . "/" . \config::app('app_module_dir') . "/" .  $_REQUEST['var'] . ".php";

					if(!is_file($lang_file)) {
						access_404();
					}
				break;

				case 'notif':
					$lang_file = $lang_folder . "/mail/" .  $_REQUEST['var'] . ".tpl";

					if(!is_file($lang_file)) {
						access_404();
					}
				break;

				case 'site':
					$lang_file = $lang_folder . "/site/" .  $_REQUEST['var'] . ".php";

					if(!is_file($lang_file)) {
						access_404();
					}
				break;

				default:
					$lang_file = $lang_folder . '/' . $_REQUEST['var'] . ".php";
			
					if(!is_file($lang_file)) {
						access_404();
					}
				break;
			}

			$lang_sourse = file_put_contents($lang_file, stripslashes($lang_sourse));
			\logs::add(\twig::$lang["translate_log_edit"].' (' . $_REQUEST['var'] . ')', \module::$log_setting);
		}

		echo json_encode(array("respons"=>\twig::$lang["form_save_success"], "status"=>"success"));
		exit;
	}

	static function translate_copy() {
		$module_info = self::module_info();

		$void_id = $_REQUEST['void_id'];
		$lang_folder = BASE_DIR . '/i18n/';

		if(!is_dir($lang_folder . $void_id . "/")) {
			access_404();
		}

		if(isset($_REQUEST['save'])) {
			$new_dir = strtolower($_REQUEST['new_lang']);

			$error = [];

			try {
				v::notEmpty()->alpha()->length(2, 2)->assert($new_dir);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["translate_error_new_lang"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(is_dir($lang_folder . $new_dir . "/")) {
				access_404();
			}

			self::rcopy($lang_folder . $void_id . "/", $lang_folder . $new_dir . "/");

			\logs::add(\twig::$lang["translate_log_copy"].' (' . $void_id . ' - ' . $new_dir . ')', \module::$log_setting);

			echo json_encode(array("ref"=>ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang='.$new_dir, "status"=>"success"));
			exit;
		}

		\twig::assign('void_id', $void_id);
		$html = \twig::fetch(self::$mod_folder . 'translate_copy.tpl');
		echo json_encode(array("title"=>\twig::$lang["translate_copy"],"html"=>$html, "status"=>"success"));
		exit;
	}

	static function rcopy($src, $dst) {
		if (is_dir($src)) {
			mkdir($dst);
			$files = scandir($src);
			foreach ($files as $file)
			if ($file != "." && $file != "..") self::rcopy("$src/$file", "$dst/$file");
		}
		else if (file_exists($src)) copy($src, $dst);
	}

}
?>
