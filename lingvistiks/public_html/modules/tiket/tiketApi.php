<?php
namespace modules;

class tiketApi extends tiket {

	// внешний метод для добавления тикетов с сайта
	static function tiket_add() {
		$module_info = self::module_info();
		$main_users = get_work_user();
		$tiket_group_list = self::get_tiket_group();


		$error = [];
		$tiket_title = "tiket ". date("d.m.Y H:i", time());
		$tiket_text = clear_tags($_REQUEST['tiket_text']);
		$tiket_group = (int)$_REQUEST['tiket_group'];
		$tiket_type_temp = $_REQUEST['tiket_type'];

		$user_email = ($_SESSION['user_id']) ? $_SESSION['user_email'] : clear_text($_REQUEST['user_email']);
		$user_name = ($_SESSION['user_id']) ? $_SESSION['user_name'] : clear_text($_REQUEST['user_name']);
		$user_phone = ($_SESSION['user_id']) ? $_SESSION['user_phone'] : clear_text($_REQUEST['user_phone']);

		if(!$_SESSION['user_id']) {
			if(mb_strlen($user_email)<\config::app('app_min_strlen')) {
				$error[] = \twig::$lang["tiket_user_email_error"];
			}

			if(mb_strlen($user_name)<\config::app('app_min_strlen')) {
				$error[] = \twig::$lang["tiket_user_name_error"];
			}
		}

		$tiket_type = [];
		foreach ($tiket_type_temp as $value) {
			if(array_key_exists($value, [1,2,3])) {
				$tiket_type[] = (int)$value;
			}
		}
		
		if(!$tiket_type_temp) {
			$error[] = \twig::$lang["tiket_type_error"];
		}
		
		if($_REQUEST['privacy'] != 1) {
			$error[] = \twig::$lang["tiket_support_secure_error"];
		}

		/*if(!array_key_exists($tiket_group, $tiket_group_list)) {
			$error[] = \twig::$lang["tiket_group_error"];
		}*/

		if(mb_strlen($tiket_text)<\config::app('app_min_strlen')) {
			$error[] = \twig::$lang["tiket_text_error"];
		}

		if($error) {
			\twig::assign('error', $error);
			$html = \twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		\DB::query("
			INSERT INTO
				" . \DB::$db_prefix . "_module_tiket
			SET
				tiket_title = '".$tiket_title."',
				tiket_text = '".$tiket_text."',
				tiket_group = '".$tiket_group."',
				tiket_user_email = '".$user_email."',
				tiket_user_name = '".$user_name."',
				tiket_user_phone = '".$user_phone."',
				tiket_type = '".serialize($tiket_type)."',
				tiket_add = '".time()."',
				tiket_owner = '".$_SESSION['user_id']."'
		");
		$tiket_id = \DB::lastInsertId();
		\logs::add(\twig::$lang["tiket_log_add"].' (' . $tiket_id . ')', \module::$log_setting);
        
        $email_body = $user_phone . "<br>" . $user_name . "<br>" . $user_email . "<br>" . $tiket_text;
        $subject = \twig::$lang["tiket_add_subject"];
        
        \sendmail::send_mail(
				\config::app('app_email_reg'),
				$email_body,
				$subject,
				\config::app('app_email_reg'),
				\config::app('SYS_MAIL_SENDER'),
				'html'
			);

		$link = HOST_NAME	. "/"
						. "?code=" . $tiket_id
						. "#feedback-after";
		echo json_encode(array("ref"=>$link, "status"=>"success"));
		exit;
	}
	// внешний метод для добавления тикетов с сайта
}
?>
