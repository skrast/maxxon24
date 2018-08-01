<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteAuth {
	static $log_setting = 2;
	static $user_group = [3, 4];

	static function user_login() {

		if (!empty($_REQUEST['user_email']) && !empty($_REQUEST['user_pass'])) {
			$browser = $_SERVER["HTTP_USER_AGENT"];
			$ip = $_SERVER['REMOTE_ADDR'];

			$ban = auth::check_ban($_REQUEST['user_email'], $ip);
			if($ban === false) {
				$try = auth::user_login($_REQUEST['user_email'], $_REQUEST['user_pass']);

				if ($try === true) {
					twig::assign('browser', $browser);
					twig::assign('ip', $ip);

					// send notif
					$main_users = get_work_user();
					$row = $main_users[$_SESSION['user_id']];
					sendmail::sendmail_bilder($row, twig::$lang['login_log'], twig::fetch('mail_login.tpl'));
					// send notif

					echo json_encode(array("ref"=> HOST_NAME , "status"=>"success"));
					exit;
				} else {

					if($try->user_status != 1 && $try->user_block_desc!='') {
						echo json_encode(array("respons"=>$try->user_block_desc, "status"=>"error"));
						exit;
					}

					echo json_encode(array("respons"=>twig::$lang["auth_pass_error"], "status"=>"error"));
					exit;

					/*$ban_count = auth::make_ban($_REQUEST['user_email'], $ip);
					$ban_allow = config::app('app_login_max_error')-$ban_count;

					$ban_allow = declension($ban_allow, twig::$lang["login_fail2ban_count"]);

					$login_make_ban = twig::$lang["login_make_ban"];
					$login_make_ban = str_replace('##COUNT##', $ban_allow, $login_make_ban);
					$login_make_ban = str_replace('##TIME##', format_interval(config::app('app_login_ban_time')), $login_make_ban);

					echo json_encode(array("respons"=>$login_make_ban, "status"=>"error"));
					exit;*/
				}
			} else {
				echo json_encode(array("respons"=>twig::$lang["auth_pass_error"], "status"=>"error"));
				exit;
			}
		}
		echo json_encode(array("respons"=>twig::$lang["auth_pass_error"], "status"=>"error"));
		exit;
	}

	static function user_register() {
		$user_password = strip_tags($_REQUEST['user_password']);
		$user_password_copy = strip_tags($_REQUEST['user_password_copy']);
		$user_email = strip_tags($_REQUEST['user_email']);
		$user_group = (int)$_REQUEST['user_group'];
		$user_promocode = (int)$_REQUEST['user_promocode'];
		$user_type_form = $_REQUEST['user_type_form'];

		if(!in_array($user_group, self::$user_group)) {
			$error[] = twig::$lang['auth_group_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}
		
		if(!$user_type_form || !in_array($user_type_form, [1,2])) {
			$error[] = twig::$lang['auth_user_type_form_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
			$error[] = twig::$lang['profile_email_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		} else {

			$test = DB::row("
				SELECT user_email FROM " . DB::$db_prefix . "_users
				WHERE
					UPPER(user_email) = UPPER('".addslashes($user_email) . "')
			");
			if($test) {
				$error[] = twig::$lang['profile_email_used_error'];

				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}
		}

		if($user_password_copy != $user_password) {
			$error[] = twig::$lang['auth_password_copy_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(mb_strlen($user_password)<config::app('app_min_strlen_password') || !preg_match('|^[a-zA-Z0-9]+$|i', $user_password)) {
			$error[] = twig::$lang['auth_password_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		

		/*if(!empty($user_promocode)) {
			$test = DB::row("
				SELECT id FROM " . DB::$db_prefix . "_users
				WHERE
					id = '".$user_promocode . "'
			");
			if(!$test) {
				$error[] = twig::$lang['auth_promocode_error'];

				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}
		}*/

		if($_REQUEST['privacy'] != 1) {
			$error[] = twig::$lang['auth_privacy_error'];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		/*if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}*/

		$newsalt = make_random_string();
		$password = password_hash($user_password, PASSWORD_DEFAULT);
		$user_secret = md5(md5(time() . $newsalt));

		$user_lang = config::get_user_lang($_REQUEST['user_lang']);

		$chars  = "abcdefghijklmnopqrstuvwxyz";
		$chars .= "ABCDEFGHIJKLMNOPRQSTUVWXYZ";
		$chars .= "0123456789";
		$emc = make_random_string(6, $chars);
		$md5_pass_salt = password_hash($user_password, PASSWORD_DEFAULT);

		$user_billing_date = time() + config::app("site_vip");

		DB::query("
			INSERT INTO " . DB::$db_prefix . "_users
			SET
				user_regtime = '".time() . "',
				user_visittime = '".time() . "',
				user_secret = '". $user_secret . "',
				user_email = '".addslashes(strip_tags($user_email))."',
				user_password = '" . addslashes($password) . "',
				user_promocode = '" . addslashes($user_promocode) . "',
				user_use_notify     = '0',
				user_get_notify_email     = '1',
				user_get_notify_phone     = '0',
				user_get_login     = '0',
				user_type_form = '".(int)$user_type_form."',
				user_lang = '".$user_lang."',
				user_group = '".$user_group."',
				user_status = '0',
				user_new_pass = '" . addslashes($md5_pass_salt) . "',
				user_billing_date = '" . $user_billing_date . "',
				user_emc = '" . addslashes($emc) . "'
		");
		$user_id = DB::lastInsertId();

		logs::add(twig::$lang['profile_add_log'] . ' (' . $user_id . ')', self::$log_setting);

		// уведомления
		$row_remind = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users
			WHERE id = '" . $user_id . "'
		");

		$email_body = twig::fetch('mail_register.tpl');
		$email_body = str_replace("%PASS%", $user_password, $email_body);
		$email_body = str_replace("%HOST%", HOST_NAME, $email_body);
		$email_body = str_replace("%LINK%",
							HOST_NAME	. "/register/confirm/"
											. "?code=" . $emc
											. "&email=" . $_REQUEST['user_email'],
							$email_body);


		sendmail::send_mail(
			$row_remind->user_email,
			$email_body,
			twig::$lang['auth_register_title'],
			config::app('app_email_reg'),
			config::app('SYS_MAIL_SENDER'),
			'html'
		);					

		//sendmail::sendmail_bilder($row_remind, twig::$lang['auth_register_title'], $email_body, true);
		// уведомления

		echo json_encode(array("respons"=>twig::$lang['auth_register_success'], "status"=>"success"));
		exit;
	}

	static function user_register_confirm() {

		if (isset($_REQUEST['code']) && !empty($_REQUEST['email']))
		{
			$row_remind = DB::row("
				SELECT
					*
				FROM " . DB::$db_prefix . "_users
				WHERE user_email   = '" . addslashes($_REQUEST['email']) . "'
				AND user_new_pass != ''
				AND user_emc  = '" . addslashes($_REQUEST['code']) . "'
				AND user_status = '0'
				LIMIT 1
			");
			if ($row_remind)
			{

				// назначаем тариф
				$set_tarif = "";
				if($_COOKIE['lending'] == 1 && $row_remind->user_group == 3) {
					$tarif_set = 3;
					$price_set = 1;
					$app_tariff = siteBilling::get_tarif_group($row_remind->user_group, $row_remind->user_type_form);
					$billing_date = time() + ($app_tariff[$tarif_set]['price'][$price_set]["dest"]*86400);

					$set_tarif = ",
						user_billing = '".$tarif_set."',
						user_billing_price = '1',
						user_balance = '".$app_tariff[$tarif_set]['price'][$price_set]['price']."',
						user_billing_date = '".$billing_date."',
						user_last_billing = '0'
					";

					unset($_COOKIE['lending']);
				}
				// назначаем тариф


				DB::query("
					UPDATE " . DB::$db_prefix . "_users
					SET
						user_password = '" . addslashes($row_remind->user_new_pass) . "',
						user_status = '1'
						$set_tarif
					WHERE user_email  = '" . addslashes($_REQUEST['email']) . "'
				");

				// сообщение от бота
				siteBot::velcom($row_remind->id);
				// сообщение от бота
			}
		}

		header('Location:' . HOST_NAME."#login");
		exit;
	}

	// получаем информацию о пользователе: фио и аватар
	static function get_profile() {
		sleep(1);

		$by_type = clear_text($_REQUEST['void_type']);
		$by_value = clear_text($_REQUEST['void_data']);

		if(empty($by_value)) {
			return false;
		}

		switch ($by_type) {
			case 'reset_by_email':
				$find = "UPPER(user_email) = UPPER('".addslashes($by_value) . "')";
			break;

			default:
				$find = "user_phone = '".clear_phone_to_int($by_value) . "'";
			break;
		}

		$row_remind = DB::row("
			SELECT
				*
			FROM " . DB::$db_prefix . "_users
			WHERE $find
			LIMIT 1
		");

		if($row_remind) {
			$profile_info = profile::profile_info($row_remind);

			echo json_encode(array("respons"=>['name'=>$profile_info->user_name, 'photo'=>$profile_info->user_photo_path], "status"=>"success"));
			exit;
		}
	}
	// получаем информацию о пользователе: фио и аватар

	// отправка кода восстановления для профиля
	static function user_recover() {
		sleep(1);

		$by_value = clear_text($_REQUEST['reset_by_email']);

		if(!filter_var($by_value, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(array("respons"=>twig::$lang['auth_reset_pass_email_error'], "status"=>"error"));
			exit;
		}

		$row_remind = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users
			WHERE user_email = '" . addslashes($by_value) . "'
		");

		if ($row_remind)
		{
			$row_remind->user_get_notify_email = 1;
			$chars  = "abcdefghijklmnopqrstuvwxyz";
			$chars .= "ABCDEFGHIJKLMNOPRQSTUVWXYZ";
			$chars .= "0123456789";
			$emc = make_random_string(6, $chars);

			DB::query("
				UPDATE " . DB::$db_prefix . "_users
				SET
					user_emc = '" . addslashes($emc) . "'
				WHERE user_email = '" . addslashes($by_value) . "'
				LIMIT 1
			");


			$email_body = twig::fetch('mail_password_recover.tpl');

			$link = HOST_NAME	. "/"
							. "?code=" . $emc
							. "&email=" . $by_value
							. "#recover";

			$email_body = str_replace("%HOST%", HOST_NAME, $email_body);
			$email_body = str_replace("%CODE%", $emc, $email_body);
			$email_body = str_replace("%LINK%", $link, $email_body);

			sendmail::send_mail(
				$row_remind->user_email,
				$email_body,
				twig::$lang['auth_reset_pass'],
				config::app('app_email_reg'),
				config::app('SYS_MAIL_SENDER'),
				'html'
			);	
			//sendmail::sendmail_bilder($row_remind, twig::$lang['auth_reset_pass'], $email_body, true);

			$link = HOST_NAME	. "/"
							. "?email=" . $by_value
							. "#recover";

			echo json_encode(array("ref"=>$link, "status"=>"success"));
		} else {
			echo json_encode(array("respons"=>twig::$lang['auth_reset_pass_email_error'], "status"=>"error"));
		}

		exit;
	}
	// отправка кода восстановления для профиля

	// верификация кода подтверждения и сброс пароля
	static function user_recover_verify() {


		

		$row_remind = DB::row("
			SELECT
				id
			FROM " . DB::$db_prefix . "_users
			WHERE (user_email = '" . addslashes($_REQUEST['user_email']) . "' OR user_login = '" . addslashes($_REQUEST['user_email']) . "')
			AND user_emc  = '" . addslashes($_REQUEST['code']) . "'
			LIMIT 1
		");

		if (
			!isset($_REQUEST['code']) || !$row_remind
		) {
			echo json_encode(array("respons"=>twig::$lang['auth_recover_code_empty_error'], "status"=>"error"));
			exit;
		}


		if(!preg_match("/^[a-z0-9_]+$/i", $_REQUEST['user_password'])) {
			echo json_encode(array("respons"=>twig::$lang['auth_recover_password_not_correct_error'], "status"=>"error"));
			exit;
		}

		if (
			empty($_REQUEST['user_email']) ||
			empty($_REQUEST['user_password']) ||
			$_REQUEST['user_password'] != $_REQUEST['user_password_copy']
		) {
			echo json_encode(array("respons"=>twig::$lang['auth_recover_password_error'], "status"=>"error"));
			exit;
		}

		if ($row_remind)
		{
			$hash = password_hash($_REQUEST['user_password'], PASSWORD_DEFAULT);

			DB::query("
				UPDATE " . DB::$db_prefix . "_users
				SET
					user_password = '" . $hash . "',
					user_new_pass = '" . $hash . "',
					user_emc = ''
				WHERE user_email  = '" . addslashes($_REQUEST['user_email']) . "'
			");

			$email_body = twig::fetch('mail_password_recover_success.tpl');
			//sendmail::sendmail_bilder($row_remind, twig::$lang['auth_recover_success'], $email_body, true);

			sendmail::send_mail(
				$row_remind->user_email,
				$email_body,
				twig::$lang['auth_recover_success'],
				config::app('app_email_reg'),
				config::app('SYS_MAIL_SENDER'),
				'html'
			);

			echo json_encode(array("respons"=>twig::$lang['auth_recover_success'], "status"=>"success"));
			exit;
		}
	}
	// верификация кода подтверждения и сброс пароля

}
?>
