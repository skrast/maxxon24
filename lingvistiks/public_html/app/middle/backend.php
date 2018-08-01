<?php
class backend {
	static $log_setting = 1;

	function __construct()
    {

    }

    static function bild_main() {
		if(empty($_SESSION['user_id']) || (!$_SESSION['main_access'] && !$_SESSION['alles'])) {
			header('Location:'.ABS_PATH.'auth.php');
			exit;
		}

		// security
		if(backend::security()===false) {
			header('Location:auth.php');
			exit;
		}
		// security

		module::get_active_modules();

		if(backend::isAjax() || backend::isOutside()) {
			$sub = $_REQUEST['sub'];
			$ajax = new ajax;
			if(method_exists($ajax, $sub)) {
	            ajax::$sub();
	        }
		}

		// подключение классов
		$do = (isset($_REQUEST['do'])) ? $_REQUEST['do'] : 'start';
		$sub = (isset($_REQUEST['sub'])) ? $_REQUEST['sub'] : 'bild_page';

		if(class_exists($do)) {
		    if(method_exists($do, $sub)) {
				new $do();
		        $do::$sub();

				// title
				if(property_exists($do, 'main_title')) {
					backend::bild_title($do::$main_title);
				}
				// title
		    } else {
		        access_404();
		    }
		} else {
		    access_404();
		}
		// подключение классов

		// page
		twig::assign('module_list', module::$modules_list);
		twig::assign('bild_profile', twig::fetch('chank/bild_profile.tpl'));
		twig::assign('bild_top_link', twig::fetch('chank/bild_top_link.tpl'));
		if(isset($_SESSION['user_use_notify'])) {
			twig::assign('bild_dialog', twig::fetch('chank/bild_dialog.tpl'));
		}

		twig::assign('hook_main', hook::show_hook('hook_main'));
		// page

		// PROFILING
		if (config::app('app_profiling') && $_SESSION['alles']) {
		    twig::assign('get_statistic', DB::get_statistic());
		}
		// PROFILING

		twig::display('backend.tpl');
	}

	static function bild_404() {
		if(empty($_SESSION['user_id'])) {
			header('Location:'.ABS_PATH.'auth.php');
			exit;
		}

		// security
		if(backend::security()===false) {
			header('Location:auth.php');
			exit;
		}
		// security

		backend::bild_title(array(twig::$lang['noaccess_name']));

		module::get_active_modules();

		// page
		twig::assign('module_list', module::$modules_list);
		twig::assign('bild_profile', twig::fetch('chank/bild_profile.tpl'));
		twig::assign('bild_top_link', twig::fetch('chank/bild_top_link.tpl'));
		if(isset($_SESSION['user_use_notify'])) {
			twig::assign('bild_dialog', twig::fetch('chank/bild_dialog.tpl'));
		}
		// page

		// PROFILING
		if (config::app('app_profiling') && $_SESSION['alles']) {
		    twig::assign('get_statistic', DB::get_statistic());
		}
		// PROFILING

		twig::assign('content', twig::fetch('no_access.tpl'));
		twig::display('backend.tpl');
		exit;
	}

	static function bild_title($title) {
		$title = array_unique($title);
		$page_title = implode(". ", array_reverse($title));
		$MAIN_TITLE = $page_title . ". " .config::app('app_name')." ".config::app('app_ver');
		twig::assign('MAIN_TITLE', $MAIN_TITLE);
	}

    static function check_base() {
		if (! empty($_REQUEST['thumb'])) {
			thumb::bild_thumb();
		}

		if (!$_SESSION['user_id'] || !$_SESSION['user_group']) {
		    header('Location:auth.php');
		    exit;
		}
	}

    static function isAjax() {
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}

    static function isOutside() {
		return !empty($_REQUEST['outside']) && $_REQUEST['outside'] == '1';
	}

    static function security() {
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$security = get_security();
		if($security[1]->status == 1 && !empty($security[1]->ip)) {
			$white_ip = explode("\n", $security[1]->ip);

            $check_ip_white = [];
			foreach ($white_ip as $key => $value) {
				$check_ip_white[] = trim($value);
			}

			if(in_array($user_ip, $check_ip_white)) {
				return true;
			} else {
				return false;
			}
		}

		if($security[2]->status == 1 && !empty($security[2]->ip)) {
			$black_ip = explode("\n", $security[2]->ip);

            $check_ip_black = [];
			foreach ($black_ip as $key => $value) {
				$check_ip_black[] = trim($value);
			}

			if(in_array($user_ip, $check_ip_black)) {
				return false;
			} else {
				return true;
			}
		}

		return true;
	}

	static function prepare_base() {
		config::work();

		// https
		if(config::app('app_use_https') == true && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")) {
			$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $redirect");
			exit;
		}
		// https

		// work folder
		$folder_array = array(
			config::app('app_attach_dir'),
			config::app('app_cron_dir'),
			config::app('app_session_dir'),
			config::app('app_storage_dir'),
			config::app('app_twig_dir'),
			config::app('app_update_dir'),
			config::app('app_classmap_dir'),
			config::app('app_import_dir'),
		);
		foreach($folder_array as $dir)
		{
			if(!is_dir(BASE_DIR . '/' . $dir)) {
				@mkdir(BASE_DIR . '/' . $dir);
			}
		}
		foreach($folder_array as $dir)
		{
			write_htaccess_deny(BASE_DIR . '/' . $dir);
		}
		// work folder

		// file folder
		$folder_array = array(
			//config::app('app_upload_dir') .'/'. config::app('app_model_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_users_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_users_orig_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_resume_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_resume_orig_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_album_resume_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_album_dir'),
			//config::app('app_upload_dir') .'/'. config::app('app_project_dir'),
			//config::app('app_upload_dir') .'/'. config::app('app_logo_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_module_dir'),
			config::app('app_upload_dir') .'/'. config::app('app_message_dir'),
			config::app('app_module_dir')
		);
		foreach($folder_array as $dir)
		{
			if(!is_dir(BASE_DIR . '/' . $dir)) {
				@mkdir(BASE_DIR . '/' . $dir);
				@chmod(BASE_DIR . '/' . $dir, 0755);
			}
		}
		// file folder
	}
}
?>
