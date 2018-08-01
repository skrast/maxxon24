<?php
class twig {
	static $twig;
	static $twig_array;

	static $lang = array();

	function __construct() {

	}

	static function bild_twig() {
		require_once(BASE_DIR . '/vendor/twig/vendor/autoload.php');
		require_once(BASE_DIR . '/vendor/twig/extensions/vendor/autoload.php');

		$twig_loader = new Twig_Loader_Filesystem(
			[
				BASE_DIR . "/assets/templates/",
				BASE_DIR . '/' . config::app('app_module_dir') .'/',
				BASE_DIR . '/i18n/'.config::get_user_lang($_SESSION['user_lang']).'/mail/'
			]
		);

		self::$twig = new Twig_Environment($twig_loader, array(
		    'cache' => BASE_DIR . "/" . config::app('app_twig_dir'),
		    'auto_reload' => config::app('app_twig_compile_check'),
		    'autoescape' => false,
		));

		// stripslashes
		self::$twig->addFilter(new Twig_SimpleFilter('stripslashes', function ( $string ) {
			return stripslashes( $string );
		}));
		// stripslashes

		// ntobr
		self::$twig->addFilter(new Twig_SimpleFilter('ntobr', function ( $string ) {
			return str_replace("\n", "<br>", $string);
		}));
		// ntobr

		// only_date
		self::$twig->addFilter(new Twig_SimpleFilter('only_date', function ( $string ) {
			$string = explode(" ", $string);
			return $string[0];
		}));
		// only_date

		// cito
		self::$twig->addFilter(new Twig_SimpleFilter('cito', function ( $string ) {
			return preg_replace('|``(.*)``|isU', '<blockquote>$1</blockquote>', $string);
		}));
		// cito

		// truncate
		self::$twig->addFilter(new Twig_SimpleFilter('truncate', function ( $string, $size ) {
			if(mb_strlen($string) <= $size) {
		        return $string;
		    } else {
		        return mb_substr($string, 0, $size) . "...";
		    }
		}));
		// truncate

		// int format
		self::$twig->addFilter(new Twig_SimpleFilter('format_number', function ( $string ) {
			return format_string_number($string);
		}));
		self::$twig->addFilter(new Twig_SimpleFilter('format_number_tranc', function ( $string ) {
			return format_string_number_tranc($string);
		}));
		// int format

		// int format
		self::$twig->addFilter(new Twig_SimpleFilter('format_year', function ( $string ) {
			return declension($string, twig::$lang['lk_info_experience_year']);
		}));
		// int format

		// int format
		self::$twig->addFilter(new Twig_SimpleFilter('implode_array', function ( $string, $separator ) {
			return implode($separator." ", $string);
		}));
		// int format

		// convert_int_to_str
		self::$twig->addFilter(new Twig_SimpleFilter('convert_int_to_str', function ( $string ) {
			return number2string($string);
		}));
		// convert_int_to_str

		// date format
		self::$twig->addFilter(new Twig_SimpleFilter('format_date', function ( $string ) {
			return unix_to_date($string);
		}));
		// date format

		// lang
		twig::set_default_variable();
		// lang
	}

	static function fetch($template) {
		return self::$twig->render($template, self::$twig_array);
	}

	static function display($template) {
		echo self::$twig->render($template, self::$twig_array);
	}

	static function assign($name, $data) {
		self::$twig_array[$name] = $data;
	}

	// подключение языковых файлов
	static function set_default_lang() {

		twig::$lang = [];
		$lang_folder = BASE_DIR."/i18n/".config::get_user_lang($_SESSION['user_lang'])."/";
		$files = scandir($lang_folder);

		foreach($files as $file) {
			if(is_file($lang_folder . $file) === true) {
				include($lang_folder.$file);
				twig::$lang = array_merge(twig::$lang, $lang);
			}
		}

		$lang_folder_module = $lang_folder . config::app('app_module_dir') .'/';
		$files = scandir($lang_folder_module);
		foreach($files as $file) {
			if(is_file($lang_folder_module . $file) === true) {
				include($lang_folder_module.$file);
				twig::$lang = array_merge(twig::$lang, $lang);
			}
		}

		$lang_folder_site = $lang_folder . config::app('app_site_dir') .'/';
		$files = scandir($lang_folder_site);
		foreach($files as $file) {
			if(is_file($lang_folder_site . $file) === true) {
				include($lang_folder_site.$file);
				twig::$lang = array_merge(twig::$lang, $lang);
			}
		}

		twig::assign('lang', twig::$lang);
	}
	// подключение языковых файлов

	static function set_default_variable() {
		// lang
		twig::set_default_lang();
		// lang

		// variant
		twig::assign('BASE_DIR', BASE_DIR);
		twig::assign('ABS_PATH', ABS_PATH);
		twig::assign('ABS_PATH_ADMIN_LINK', ABS_PATH_ADMIN_LINK);
		twig::assign('HOST', HOST);
		twig::assign('HOST_NAME', HOST_NAME);
		twig::assign('SESSION', $_SESSION);
		twig::assign('REQUEST', $_REQUEST);
		twig::assign('bild_spinner', twig::fetch('chank/bild_spinner.tpl'));
		// variant

		config::$app['app_lang'] = config::get_user_lang($_SESSION['user_lang']);
		twig::assign('app', config::app());
		// global
	}
}
?>
