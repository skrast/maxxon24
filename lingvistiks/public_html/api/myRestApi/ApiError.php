<?php
class ApiError extends Exception {
	static $my_code;
	static $lang;

	function __construct($code) {
		include(BASE_DIR . '/i18n/'.\config::app('app_lang').'/api.php');

        self::$lang = $lang['api_error'];
		self::$my_code = $code;

		parent::__construct(self::$lang[$code], $code);
	}

	static function getMyCode () {
		return self::$my_code;
	}
}
?>
