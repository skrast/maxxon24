<?php
class frontend {
	static $log_setting = 1;

	function __construct()
    {

	}
	
	static function check_base() {
		if (! empty($_REQUEST['thumb'])) {
			thumb::bild_thumb();
		}
	}

	static function bild_front() {

		// подключение классов
		switch ($_REQUEST['do']) {
			case 'restore':
				if ($_REQUEST['restore'] && $_REQUEST['restore'] != '') {
					self::password_reminder();
				}
				$tpl = 'restore';
			break;

			case 'login':
			default:
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

							header('Location:'.ABS_PATH_ADMIN_LINK.'?do=start');
							exit;
						} else {
							$ban_count = auth::make_ban($_REQUEST['user_email'], $ip);
							$ban_allow = config::app('app_login_max_error')-$ban_count;

							$ban_allow = declension($ban_allow, twig::$lang["login_fail2ban_count"]);

							$login_make_ban = twig::$lang["login_make_ban"];
							$login_make_ban = str_replace('##COUNT##', $ban_allow, $login_make_ban);
							$login_make_ban = str_replace('##TIME##', format_interval(config::app('app_login_ban_time')), $login_make_ban);

							twig::assign('login_make_ban', $login_make_ban);
						}
					} else {
						twig::assign('login_fail2ban', 1);
					}
				}
				$tpl = 'auth';
			break;
		}
		// подключение классов

		twig::assign('content', twig::fetch('auth/'.$tpl.'.tpl'));
		twig::display('frontend.tpl');
	}

	// восстановление пароля
	public static function password_reminder() {
		if (isset($_REQUEST['code']) && !empty($_REQUEST['email']))
		{
			$row_remind = DB::row("
				SELECT
					user_new_pass
				FROM " . DB::$db_prefix . "_users
				WHERE user_email   = '" . addslashes($_REQUEST['email']) . "'
				AND user_new_pass != ''
				AND user_emc  = '" . addslashes($_REQUEST['code']) . "'
				LIMIT 1
			");
			if ($row_remind)
			{
				DB::query("
					UPDATE " . DB::$db_prefix . "_users
					SET
						user_password = '" . addslashes($row_remind->user_new_pass) . "'
					WHERE user_email  = '" . addslashes($_REQUEST['email']) . "'
				");

				header('Location:'.ABS_PATH_ADMIN_LINK.'auth.php?success=restore');
				exit;
			}
		}

		$row_remind = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users
			WHERE user_email = '" . addslashes($_REQUEST['user_email']) . "'
		");

		if ($row_remind)
		{
			$row_remind->user_get_notify_email = 1;
			$chars  = "abcdefghijklmnopqrstuvwxyz";
			$chars .= "ABCDEFGHIJKLMNOPRQSTUVWXYZ";
			$chars .= "0123456789";
			$newpass = make_random_string(8, $chars);
			$emc = make_random_string(8, $chars);
			$md5_pass_salt = password_hash($newpass, PASSWORD_DEFAULT);

			DB::query("
				UPDATE " . DB::$db_prefix . "_users
				SET
					user_new_pass = '" . addslashes($md5_pass_salt) . "',
					user_emc = '" . addslashes($emc) . "'
				WHERE user_email = '" . addslashes($_REQUEST['user_email']) . "'
				LIMIT 1
			");


			$email_body = twig::fetch('mail_password_reminder.tpl');

			$email_body = str_replace("%PASS%", $newpass, $email_body);
			$email_body = str_replace("%HOST%", get_home_link(), $email_body);
			$email_body = str_replace("%CODE%", $emc, $email_body);
			$email_body = str_replace("%LINK%",
								get_home_link()	. "auth.php"
												. "?do=restore"
												. "&restore=1"
												. "&code=" . $emc
												. "&email=" . $_REQUEST['user_email'],
								$email_body);

			sendmail::sendmail_bilder($row_remind, twig::$lang['login_pass_title'], $email_body, true);

			header('Location:'.ABS_PATH_ADMIN_LINK.'auth.php?do=restore&success=restore');
			exit;
		}
	}
}
?>
