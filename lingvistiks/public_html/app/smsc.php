<?php

class smsc {


	function __construct() {

	}

	static function setup() {
		define("SMSC_LOGIN", config::app('app_smsc_login'));			// логин клиента
		define("SMSC_PASSWORD", config::app('app_smsc_pass'));	// пароль или MD5-хеш пароля в нижнем регистре
		define("SMSC_POST", 0);					// использовать метод POST
		define("SMSC_HTTPS", 0);				// использовать HTTPS протокол
		define("SMSC_CHARSET", "utf-8");	// кодировка сообщения: utf-8, koi8-r или windows-1251 (по умолчанию)
		define("SMSC_DEBUG", 0);				// флаг отладки
		define("SMTP_FROM", "api@smsc.ru");     // e-mail адрес отправителя
	}

	static function send_sms($to, $text) {
		
		self::setup(); // настройка модуля отправки

		include(BASE_DIR . '/app/smsc/smsc_api.php');

		DB::query("
			INSERT INTO
				" . DB::$db_prefix . "_module_smsc
			SET
				sms_to = '".clear_phone_to_int($to)."',
				sms_text = '".clear_text($text)."',
				sms_date = '".time()."',
				sms_owner = '". $_SESSION['user_id'] ."'
		");

		return smsc_api::send_sms($to, $text, 1);
	}
}

?>
