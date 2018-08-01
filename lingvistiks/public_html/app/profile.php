<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class profile {
	static $log_setting = 2;
	static $main_title =  array();

	function __construct() {
		self::$main_title[] =  twig::$lang["profile_name"];
	}

	static function bild_page() {
		if(!$_SESSION['alles']) {
			access_404();
		}

		get_work_user('-1');

		$sql_link = "";
		$page_link = (isset($_REQUEST['filter'])) ? "&filter=1" : "";

		if(isset($_REQUEST['user_status'])) {
			switch ($_REQUEST['user_status']) {
				case '1':
					$sql_link .= "AND us.user_status = '1'";
					$page_link .= "&user_status=1";
					break;

				case '2':
					$sql_link .= " AND us.user_status = '0' ";
					$page_link .= "&user_status=2";
					break;

				default:
					$sql_link .= "";
					$page_link .= "";
					break;
			}
		}

		$groups = get_work_group();
		twig::assign('groups', $groups);
		if(!empty($_REQUEST['user_group'])) {
			if(array_key_exists($_REQUEST['user_group'], $groups)) {
				$sql_link .= " AND us.user_group = '".(int)$_REQUEST['user_group']."' ";
				$page_link .= "&user_group=".(int)$_REQUEST['user_group'];
			}
		}

		// pager
		$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
		// pager

		$users = DB::fetchrow("
			SELECT
			*
			FROM " . DB::$db_prefix . "_users as us
			JOIN " . DB::$db_prefix . "_users_groups as gg on gg.user_group = us.user_group
			WHERE 1=1 $sql_link
			ORDER BY us.id ASC
			LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE') . "
		");
		foreach ($users as $row) {
			$row = profile::profile_info($row);

			twig::assign('user', $row);
			$row->item_user = twig::fetch('chank/item_user.tpl');
		}
		twig::assign('users', $users);

		// pager
		$num = DB::numrows("
			SELECT
				us.id
			FROM " . DB::$db_prefix . "_users as us
			JOIN " . DB::$db_prefix . "_users_groups as gg on gg.user_group = us.user_group
			WHERE 1=1 $sql_link
		");
		twig::assign('num', $num);
		if ($num > config::app('SYS_PEAR_PAGE'))
		{
			$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=profile'.$page_link.'&page={s}">{t}</a>');
			twig::assign('page_nav', $page_nav);
		}
		// pager
		twig::assign('content', twig::fetch('profile/profile_list.tpl'));
	}

	static function profile_work() {
		$user_id = (int)$_REQUEST['user_id'];
		if($user_id) {
			$user_info = get_user_info($user_id);
			twig::assign('user_info', $user_info);

			self::$main_title[] = $user_info->user_name;
		}

		if($user_id && !$user_info) {
			access_404();
		}

		if($user_id != $_SESSION['user_id'] && !$_SESSION['alles']) {
			access_404();
		}

		$user_permission = twig::$lang["user_permission"];

		// сброс кеша для пользователей
		get_work_user('-1');
		// сброс кеша для пользователей

		// временные зоны
		$zone = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_timezone_zone
			ORDER BY zone_id ASC
		", config::app('CACHE_LIFETIME_LONG'));
		twig::assign('zone', $zone);
		// временные зоны

		// доступные языковые версии
		$lang_array = config::get_lang();
		twig::assign('lang_array', $lang_array);
		// доступные языковые версии

		/* все группы в системе */
		$groups = get_work_group();
		twig::assign('groups', $groups);
		/* все группы в системе */

		$error = array();

		$user_lang = config::get_user_lang($_REQUEST['user_lang']);

		if(isset($_REQUEST['save'])) {
			$user_firstname = strip_tags($_REQUEST['user_firstname']);
			$user_lastname = strip_tags($_REQUEST['user_lastname']);
			$user_password = strip_tags($_REQUEST['user_password']);
			$user_password_copy = strip_tags($_REQUEST['user_password_copy']);
			$user_email = strip_tags($_REQUEST['user_email']);

			if(!empty($_REQUEST['user_project']) && $_SESSION['alles'] && $project_list) {
				foreach ($_REQUEST['user_project'] as $value) {
					if(!array_key_exists($value, $project_list)) {
						$error[] = twig::$lang['profile_project_error'];
					}
				}
				$user_project = ", user_project = '". implode(",", $_REQUEST['user_project'])."' ";
			}

			$perm = [];
			foreach ($_REQUEST['user_permissions'] as $value) {
				if(!array_key_exists($value, $user_permission)) {
					$error[] = twig::$lang['profile_perm_error'];
				} else {
					$perm[] = $value;
				}
			}
			$user_permissions = ", user_permissions = '". implode("|", $perm)."' ";

			/*if(mb_strlen($user_firstname)<config::app('app_min_strlen')) {
				$error[] = twig::$lang['profile_firstname_error'];
			}

			if(mb_strlen($user_lastname)<config::app('app_min_strlen')) {
				$error[] = twig::$lang['profile_lastname_error'];
			}*/

			if($user_password_copy != $user_password) {
				$error[] = twig::$lang['profile_password_copy_error'];
			}

			if(isset($_SESSION['alles']) && $user_info->id != 1) {
				$user_group = $_REQUEST['user_group'];
				if($user_group && array_key_exists($user_group, $groups)) {
					$user_group = ", user_group = '".(int)$user_group."'";
					$user_status = ", user_status = '".(($_REQUEST['user_status'] && $user_info->user_group != 1) ? 1 : 0)."'";
				} else {
					$error[] = twig::$lang['profile_group_error'];
				}
			}

			if(isset($_SESSION['alles']) && ($user_info->user_group == 1 || $_REQUEST['user_group'] == 1)) {
				$user_status = ", user_status = '1' ";
				$user_permissions = ", user_permissions = '' ";
			}

			if($user_id && $user_info) {
				$user_secret = "";
	            if(isset($_REQUEST['user_secret_reset'])) {
	            	$newsalt = make_random_string();
		            $user_secret = ", user_secret = '".md5(md5(time() . $newsalt))."'";
		        }

				if(isset($_REQUEST['custom_field'])) {
					$custom_field = $_REQUEST['custom_field'];
					$field_required = fields::save_custom_fields($user_id, 6, $custom_field);
					if($field_required) {
						$error = array_merge($error, $field_required);
					}
				}

			} else {
				if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
					$error[] = twig::$lang['profile_email_error'];
				} else {

					$test = DB::row("
						SELECT user_email FROM " . DB::$db_prefix . "_users
						WHERE
							UPPER(user_email) = UPPER('".addslashes($user_email) . "')
					");
					if($test) {
						$error[] = twig::$lang['profile_email_used_error'];
					}
				}
			}

			if($error) {
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(backend::isAjax()) {
				echo json_encode(array("upload"=>true, "status"=>"success"));
				exit;
			}

			if($user_id && $user_info) { // редактирование пользователя

				$photos = "";
				if($_FILES['user_photo'] && image_get_info($_FILES['user_photo']['tmp_name'])) {
			        // сохранение фотографии
			        $targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/"; // адрес директории с изображениями
			        $photos_tmp = $_FILES['user_photo']['tmp_name']; // изображения для данных
					$photos_name = $_FILES['user_photo']['name']; // изображения для данных
					if(image_get_info($photos_tmp)) {
				        $photos = rename_file($photos_name, $targetPath); // переименовываем
					}

			        if ($photos != false) {
			            $user_photo = DB::single("SELECT user_photo FROM " . DB::$db_prefix . "_users WHERE id = '".$user_id."' ");
			            if ($user_photo) @unlink(BASE_DIR . "/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/" . $user_photo);
			            $targetFile =  $targetPath . $photos;
			            move_uploaded_file($photos_tmp, $targetFile);

			            if($user_id == $_SESSION['user_id']) {
			            	$_SESSION['user_photo']     = $photos ? $photos : "no-avatar.png";
			            }
						$photos = ", user_photo = '".$photos."' ";
			        }
			    }

			    if($_REQUEST['user_password']) {
					$password = password_hash($_REQUEST['user_password'], PASSWORD_DEFAULT);

					DB::query("
						UPDATE " . DB::$db_prefix . "_users
						SET
							user_password = '" . addslashes($password) . "'
						WHERE id = '".$user_id."'
					");

					if($user_id == $_SESSION['user_id']) {
						$_SESSION['user_password'] = $password;
					}

					if($user_id != $_SESSION['user_id']) {
						twig::assign('user_info', $user_info);
						twig::assign('password', $_REQUEST['user_password']);

						$mail_body = twig::fetch('mail_profile_change_password.tpl');
						$mail_title = twig::$lang["profile_change_password_mail"];

						sendmail::sendmail_bilder($user_info, $mail_title, $mail_body);
					}
				}
				
				$billing_date = date_to_unix($_REQUEST['billing_date']);
				$user_last_billing = $user_info->user_last_billing;
				if(in_array($user_info->user_group, [3,4])) {
					/*if(date_to_unix($_REQUEST['billing_date']) != date_to_unix($user_info->user_billing_date)) {
						$billing_date = date_to_unix($_REQUEST['billing_date']);
					}*/

					if($_REQUEST['billing_pay'] && $_REQUEST['pay_type'] && $_REQUEST['pay_tariff']) {
						$pay_tariff = explode("_", $_REQUEST['pay_tariff']);

						$user_info->user_balance = $user_info->user_balance+$_REQUEST['billing_pay'];

						if($user_info->pay_tariff) {
							$user_info->user_billing = $pay_tariff[0];
						}

						$app_tariff = config::app('app_tariff');
						$billing_date = time() + ($app_tariff[$user_info->user_group][$pay_tariff[0]]['price'][$pay_tariff[1]]["dest"]*86400);
						$user_last_billing = 0;
						
						DB::query("
							INSERT
								" . DB::$db_prefix . "_users_history_pay
							SET
								history_type = '1',
								history_owner = '".$user_info->id."',
								history_sum = '".$_REQUEST['billing_pay']."',
								history_pay_type = '".$_REQUEST['pay_type']."',
								history_date = '".time()."',
								history_desc = '" . twig::$lang["billing_history_bank"] . " " . time() ."'
						");
					}
				}

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users
					SET
						user_firstname = '".addslashes(strip_tags($_REQUEST['user_firstname']))."',
						user_patronymic = '".addslashes(strip_tags($_REQUEST['user_patronymic']))."',
						user_lastname = '".addslashes(strip_tags($_REQUEST['user_lastname']))."',
						user_block_desc = '".addslashes(strip_tags($_REQUEST['user_block_desc']))."',
						user_phone = '".clear_phone_to_int($_REQUEST['user_phone'])."',
						user_skype = '".addslashes(strip_tags($_REQUEST['user_skype']))."',
						user_use_notify     = '" . ((isset($_REQUEST['user_use_notify']) && $_REQUEST['user_use_notify'] == 1) ? 1 : 0) . "',
						user_get_notify_email     = '" . ((isset($_REQUEST['user_get_notify_email']) && $_REQUEST['user_get_notify_email'] == 1) ? 1 : 0) . "',
						user_get_notify_phone     = '" . ((isset($_REQUEST['user_get_notify_phone']) && $_REQUEST['user_get_notify_phone'] == 1) ? 1 : 0) . "',
						user_get_login     = '" . ((isset($_REQUEST['user_get_login']) && $_REQUEST['user_get_login'] == 1) ? 1 : 0) . "',
						user_lang = '".$user_lang."',
						user_balance = '".$user_info->user_balance."',
						user_billing = '".$user_info->user_billing."',
						user_billing_date = '".$billing_date."',
						user_last_billing = '".$user_last_billing."'
						$user_group
						$user_status
						$photos
						$user_secret
						$user_project
						$user_permissions
						
					WHERE id = '".$user_id."'
				");

				if($user_id == $_SESSION['user_id']) {
					$user_info = get_user_info();
					auth::set_auth_param($user_info, $user_info->user_password);
				}

				logs::add(twig::$lang["profile_edit_log"] . ' (' . $user_id . ')', self::$log_setting);
				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=profile&sub=profile_work&user_id='.$user_id);
				exit;
			} else { // добавление нового

				if(!isset($_SESSION['alles'])) {
					access_404();
				}

				$photos = "";
				if($_FILES['user_photo'] && image_get_info($_FILES['user_photo']['tmp_name'])) {
		        	// сохранение фотографии
			        $targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/"; // адрес директории с изображениями
			        $photos_tmp = $_FILES['user_photo']['tmp_name']; // изображения для данных
					$photos_name = $_FILES['user_photo']['name']; // изображения для данных
					if(image_get_info($photos_tmp)) {
				        $photos = rename_file($photos_name, $targetPath); // переименовываем
					}

			        if ($photos != false) {
			            $targetFile =  $targetPath . $photos;
			            move_uploaded_file($photos_tmp, $targetFile);
						$photos = ", user_photo = '".$photos."' ";
			        }
			    }

		    	$newsalt = make_random_string();
				$password = password_hash($user_password, PASSWORD_DEFAULT);
				$user_secret = md5(md5(time() . $newsalt));

				$user_status = ", user_status = '".(($_REQUEST['user_status']) ? 1 : 0)."'";

				DB::query("
					INSERT INTO " . DB::$db_prefix . "_users
					SET
						user_regtime = '".time() . "',
						user_visittime = '".time() . "',
						user_secret = '". $user_secret . "',
						user_firstname = '".addslashes(strip_tags($_REQUEST['user_firstname']))."',
						user_lastname = '".addslashes(strip_tags($_REQUEST['user_lastname']))."',
						user_patronymic = '".addslashes(strip_tags($_REQUEST['user_patronymic']))."',
						user_block_desc = '".addslashes(strip_tags($_REQUEST['user_block_desc']))."',
						user_phone = '".clear_phone_to_int($_REQUEST['user_phone'])."',
						user_skype = '".addslashes(strip_tags($_REQUEST['user_skype']))."',
						user_email = '".addslashes(strip_tags($user_email))."',
						user_password = '" . addslashes($password) . "',
						user_use_notify     = '" . (($_REQUEST['user_use_notify'] == 1) ? 1 : 0) . "',
						user_get_notify_email     = '" . (($_REQUEST['user_get_notify_email'] == 1) ? 1 : 0) . "',
						user_get_notify_phone     = '" . (($_REQUEST['user_get_notify_phone'] == 1) ? 1 : 0) . "',
						user_get_login     = '" . (($_REQUEST['user_get_login'] == 1) ? 1 : 0) . "',
						user_lang = '".$user_lang."'
						$user_group
						$user_status
						$photos
				");
				$user_id = DB::lastInsertId();

				logs::add(twig::$lang['profile_add_log'] . ' (' . $user_id . ')', self::$log_setting);
				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=profile&sub=profile_work&user_id='.$user_id);
			}
		}


		twig::assign('user_permissions', $user_permission);
		twig::assign('content', twig::fetch('profile/profile_work.tpl'));
	}

	static function open() {
		$user_id = $_REQUEST['user_id'] ? (int)$_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_info = get_user_info($user_id);

		self::$main_title[] = $user_info->user_name;

		if($user_info) {
			twig::assign('user', $user_info);
			$user_info->item_user = twig::fetch('chank/item_user.tpl');
			twig::assign('user_info', $user_info);

			// активность
			$end_time = time();
			$day_array_temp = array();
			for ($i=$end_time-604800; $i <= $end_time; $i++) {
				$i = $i+86400;
				$day_array_temp[] = date('d.m', $i);
			}

			$sql = DB::fetchrow("
			  SELECT
			  	DATE_FORMAT(FROM_UNIXTIME(log_time),'%d.%m') as period,
			    COUNT(log_time) AS sum
			  FROM " . DB::$db_prefix . "_logs
			  WHERE log_type = '0' AND log_user_id = '".$user_id."'
			  GROUP BY period
			  ORDER BY log_time DESC
			  LIMIT 7
			");
            $check_sum = array();
            $period = array();
			foreach ($sql as $row) {
				$check_sum[$row->period] = $row->sum;
			}
			foreach ($day_array_temp as $key => $value) {
				$period[] = $value;
				$sum[] = (($check_sum[$value]) ? $check_sum[$value] : 0);
			}
			twig::assign('sum', json_encode($sum));
			twig::assign('period', json_encode($period));
			// активность

			// kpi график
			$end_time = time();
			$month_array_temp = array();
			$month_interval = $end_time-2592000*13;
			for ($i=$month_interval; $i <= $end_time; $i++) {
				$i = $i+2592000;
				$month_array_temp[] = date('m.Y', $i);
			}
			// kpi график

			twig::assign('content', twig::fetch('profile/profile_open.tpl'));
		} else {
			access_404();
		}
	}

	static function profile_info($profile_info) {
		$user_group = get_work_group();
		$profile_info->user_group_info = $user_group[$profile_info->user_group];

		$profile_info->user_online_status = (time() <= $profile_info->user_visittime+600) ? 1 : 0;

		$profile_info->user_regtime = unix_to_date($profile_info->user_regtime);
		$profile_info->user_birthday_clean = date("d.m.Y", $profile_info->user_birthday);
		$profile_info->user_birthday = unix_to_date($profile_info->user_birthday);
		$profile_info->user_visittime = unix_to_date($profile_info->user_visittime);
		$profile_info->user_billing_date = unix_to_date($profile_info->user_billing_date);
		$profile_info->user_login = $profile_info->user_login ? $profile_info->user_login : 'NO NAME';
		$profile_info->user_name = get_username($profile_info->user_firstname, $profile_info->user_lastname, $profile_info->user_login);
		$profile_info->user_phone = $profile_info->user_phone ? $profile_info->user_phone : '';
		$profile_info->user_photo = $profile_info->user_photo ? $profile_info->user_photo : "no-avatar.png";
		$profile_info->user_photo_path = config::app('app_upload_dir') . "/" . config::app('app_users_dir') . "/" . $profile_info->user_photo;

		$profile_info->user_project = $profile_info->user_project ? explode(",", $profile_info->user_project) : "";
		$profile_info->user_pays = $profile_info->user_pays ? explode(",", $profile_info->user_pays) : "";
		$profile_info->user_notice_var = $profile_info->user_notice_var ? explode(",", $profile_info->user_notice_var) : "";
		$profile_info->user_theme = $profile_info->user_theme ? explode(",", $profile_info->user_theme) : "";

		$profile_info->user_link = twig::fetch('chank/bild_profile_link.tpl');

		$profile_info->full_width = 150;
		$profile_info->small_width = 80;
		$profile_info->star_width_full = 30;
		$profile_info->star_width_small = 16;
		$profile_info->my_width_full = round($profile_info->star_width_full*$profile_info->user_rang, 2);
		$profile_info->my_width_small = round($profile_info->star_width_small*$profile_info->user_rang, 2);

		$profile_info->my_width_full = ($profile_info->my_width_full >= $profile_info->full_width) ? $profile_info->full_width : $profile_info->my_width_full;
		$profile_info->my_width_small = ($profile_info->my_width_small >= $profile_info->small_width) ? $profile_info->small_width : $profile_info->my_width_small;

		twig::assign('user_profile', $profile_info);
		$profile_info->user_rating_tpl = twig::fetch('frontend/chank/profile_rating.tpl');
		$profile_info->user_rating_small_tpl = twig::fetch('frontend/chank/profile_rating_small.tpl');

		// скилл
		$profile_info->skill_list = DB::fetchrow("SELECT * FROM " . DB::$db_prefix . "_users_skill WHERE skill_owner = '".$profile_info->id."'", config::app('CACHE_LIFETIME_LONG'));
		// скилл

		$profile_info->full_user_id = self::bild_full_user_id($profile_info);

		return $profile_info;
	}

	static function bild_full_user_id($profile_info) {
		$current_id = $profile_info->id;
		$current_id = self::add_zero_to_string($current_id, 9);

		return mb_substr($current_id, 0, 3) . "-" . mb_substr($current_id, 3, 3) . "-" . mb_substr($current_id, 6, 3);
	}

	static function add_zero_to_string($string, $length) {
		$curent_length = mb_strlen($string);

		for ($i=$curent_length; $i < $length; $i++) {
			$string = "0".$string;
		}

		return $string;
	}

}
?>
