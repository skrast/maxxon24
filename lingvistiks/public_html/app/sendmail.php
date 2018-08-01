<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class sendmail {
	static $main_title =  array();
	static $mail_folder = '';
    static $mail_label = '';

	function __construct() {
		self::$main_title[] =  twig::$lang["sendmail_name"];
	}

	static function bild_sendmail() {
		self::$mail_folder = twig::$lang["sendmail_folder_array"];
		self::$mail_label = twig::$lang["sendmail_label_array"];

		twig::assign('mail_folder', self::$mail_folder);
		twig::assign('mail_label', self::$mail_label);
		twig::assign('hook_sendmail_start', hook::show_hook('hook_sendmail_start', ''));
		twig::assign('sendmail_filter', twig::fetch('chank/sendmail_filter.tpl'));
	}

	static function bild_page() {


		self::bild_sendmail();

		$page_link = "";
		$sql_link = "";

		// folder
		if(isset($_REQUEST['folder']) && !is_numeric($_REQUEST['folder']) && !array_key_exists($_REQUEST['folder'], self::$mail_folder)) {
			access_404();
		}

		if(!empty($_REQUEST['q'])) {
			$query = addslashes(strip_tags($_REQUEST['q']));
			$sql_link .= " AND (mail_title LIKE '%".$query."%' OR mail_body LIKE '%".$query."%' ) ";
			$page_link .= "&q=".$query;
		}

		if(isset($_REQUEST['folder']) && is_numeric($_REQUEST['folder'])) {
			$folder = (int)$_REQUEST['folder'];

			switch ($folder) {
				case '1':
					$sql_link .= " AND mail_recipient = '".$_SESSION['user_id']."' ";
				break;

				case '2':
					$sql_link .= " AND mail_author = '".$_SESSION['user_id']."' ";
				break;

				case '3':
					$sql_link .= " AND mail_author = '".$_SESSION['user_id']."' ";
				break;

				case '4':
					$sql_link .= " AND (mail_author = '".$_SESSION['user_id']."' OR (mail_recipient = '".$_SESSION['user_id']."' AND mail_author = '0')) ";
				break;
			}
			$folder_get = array($folder);
			$page_link .= $folder ? "&folder=".(int)$folder : "";
		} else {
			$sql_link .= " AND (mail_recipient = '".$_SESSION['user_id']."' OR mail_author = '".$_SESSION['user_id']."')";
			$folder_get = array(1,2);
		}
		$sql_link .= " AND mail_folder IN (".implode(",", $folder_get).")";
		// folder

		// label
		if(isset($_REQUEST['label']) && !is_numeric($_REQUEST['label']) && !array_key_exists($_REQUEST['label'], self::$mail_label)) {
			access_404();
		}
		if(isset($_REQUEST['label']) && is_numeric($_REQUEST['label'])) {
			$page_link .= "&label=".(int)$_REQUEST['label'];
			$sql_link .= " AND mail_label = '".(int)$_REQUEST['label']."'";
		}
		// label

		$main_users = get_work_user();

		// pager
		$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
		// pager

		$mails = DB::fetchrow("
			SELECT
			*
			FROM " . DB::$db_prefix . "_sendmail
			WHERE 1=1 $sql_link
			ORDER BY mail_save DESC, mail_id DESC
			LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE') . "
		");
		foreach ($mails as $row) {
			$row->mail_save = unix_to_date($row->mail_save);
			$row->mail_folder = self::$mail_folder[$row->mail_folder];
			$row->mail_body = (mb_strlen($row->mail_body) > 100) ? mb_substr($row->mail_body, 0, 100)."..." : $row->mail_body;

			$row->mail_author = ($row->mail_author) ? $main_users[$row->mail_author] : '';
			$row->mail_recipient = ($row->mail_recipient) ? $main_users[$row->mail_recipient] : '';
		}
		twig::assign('mails', $mails);

		// pager
		$num = DB::numrows("SELECT
			mail_id
			FROM " . DB::$db_prefix . "_sendmail
			WHERE 1=1 $sql_link
		");
		twig::assign('num', $num);
		if ($num > config::app('SYS_PEAR_PAGE'))
		{
			$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=sendmail'.$page_link.'&page={s}">{t}</a>');
			twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('content', twig::fetch('sendmail/sendmail_list.tpl'));
	}

	static function compose() {

        $email_info = "";

		$signature_list = DB::fetchrow("
			SELECT
				*
			FROM " . DB::$db_prefix . "_sendmail_signature
			WHERE signature_owner = '".(int)$_SESSION['user_id']."'
			ORDER BY signature_id DESC
		");
		twig::assign('signature_list', $signature_list);

		// правим черновик
		if($_REQUEST['void_id']) {
			$email_info = DB::row("
				SELECT *
				FROM
					" . DB::$db_prefix . "_sendmail
				WHERE
					mail_id = '".(int)$_REQUEST['void_id']."' AND mail_author = '".$_SESSION['user_id']."' AND mail_draft = '1'
			");

			if($email_info) {
				$main_users = get_work_user();

				// заполняем получателей по типам
				$email_info->mail_email_company = $email_info->mail_email_company ? json_decode($email_info->mail_email_company) : "";
				$company_id = [];
				foreach ($email_info->mail_email_company as $key => $value) {
					$company_id[] = $key;
				}
				if($company_id) {
					$sql = DB::fetchrow("
						SELECT *
						FROM
							" . DB::$db_prefix . "_company
						WHERE
							company_id IN (".implode(",", $company_id).")
					");
					$company_list = [];
					foreach ($sql as $row) {
						$row = company::company_info($row);
						$company_list[$row->company_id] = $row;
					}
					foreach ($email_info->mail_email_company as $key => $value) {
						foreach ($value as $mail) {
							$email_info->mail_email_company_explode[$mail] = $company_list[$key];
						}
					}
				}

				$email_info->mail_email_user = $email_info->mail_email_user ? json_decode($email_info->mail_email_user) : "";
				$user_id = [];
				foreach ($email_info->mail_email_user as $key => $value) {
					$user_id[] = $key;
				}
				if($user_id) {
					foreach ($email_info->mail_email_user as $key => $value) {
						if(array_key_exists($key, $main_users)) {
							foreach ($value as $mail) {
								$email_info->mail_email_user_explode[$mail] = $main_users[$key];
							}
						}
					}
				}
				// заполняем получателей по типам

				twig::assign('email_info', $email_info);
			} else {
				access_404();
			}
		}
		// правим черновик

		// если пришел текст для отправки
		if(!empty($_REQUEST['request_desc'])) {
			$email_info->mail_body = $_REQUEST['request_desc'];
			twig::assign('email_info', $email_info);
		}
		// если пришел текст для отправки

		$html = twig::fetch('sendmail/sendmail_compose.tpl');
		echo json_encode(array("title"=>twig::$lang["sendmail_add"],"html"=>$html, "status"=>"success"));
		exit;
	}

	static function open() {

		self::bild_sendmail();

		$main_users = get_work_user();

		$email_info = DB::row("
			SELECT *
			FROM
				" . DB::$db_prefix . "_sendmail
			WHERE
				mail_id = '".(int)$_REQUEST['mail_id']."' AND (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."')
		");

		if($email_info) {
			$email_info->mail_email = explode(",", $email_info->mail_email);
			$email_info->mail_author = ($email_info->mail_author) ? $main_users[$email_info->mail_author] : "";
			$email_info->mail_recipient = $main_users[$email_info->mail_recipient];
			$email_info->mail_save = unix_to_date($email_info->mail_save);

			// заполняем получателей по типам
			$email_info->mail_email_dop = $email_info->mail_email_dop ? explode(",", $email_info->mail_email_dop) : "";
			$email_info->mail_email_company = $email_info->mail_email_company ? json_decode($email_info->mail_email_company) : "";
			$company_id = [];
			foreach ($email_info->mail_email_company as $key => $value) {
				$company_id[] = $key;
			}
			if($company_id) {
				$sql = DB::fetchrow("
					SELECT *
					FROM
						" . DB::$db_prefix . "_company
					WHERE
						company_id IN (".implode(",", $company_id).")
				");
				$company_list = [];
				foreach ($sql as $row) {
					$row = company::company_info($row);
					$company_list[$row->company_id] = $row;
				}
				foreach ($email_info->mail_email_company as $key => $value) {
					foreach ($value as $mail) {
						$email_info->mail_email_company_explode[$mail] = $company_list[$key];
					}
				}
			}

			$email_info->mail_email_user = $email_info->mail_email_user ? json_decode($email_info->mail_email_user) : "";
			$user_id = [];
			foreach ($email_info->mail_email_user as $key => $value) {
				$user_id[] = $key;
			}
			if($user_id) {
				foreach ($email_info->mail_email_user as $key => $value) {
					if(array_key_exists($key, $main_users)) {
						foreach ($value as $mail) {
							$email_info->mail_email_user_explode[$mail] = $main_users[$key];
						}
					}
				}
			}
			// заполняем получателей по типам

			if($email_info->mail_recipient->id == $_SESSION['user_id'] && $email_info->mail_open == 0) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_sendmail
					SET
						mail_open = '".time()."'
					WHERE
						mail_id = '".(int)$_REQUEST['mail_id']."'
				");
			}

			self::$main_title[] =  stripslashes($email_info->mail_title);

			twig::assign('email_info', $email_info);
		} else {
			access_404();
		}

		twig::assign('content', twig::fetch('sendmail/sendmail_open.tpl'));
	}

	static function save_mail() {


		$mail_title = $_REQUEST['mail_title'];
		$mail_body = $_REQUEST['mail_body'];

		$i=0;
		$error = array();
		$stop_ref = 0;

		$users = $_REQUEST['user_email'];
		$company = $_REQUEST['company_email'];
		$mail_email_dop = $_REQUEST['mail_email_dop'];

		$user_array = array();
		foreach ($users as $key => $value) {
			$user_info = DB::row("
				SELECT *
				FROM
					" . DB::$db_prefix . "_users
				WHERE
					id = '".(int)$key."'
			");

			if($user_info && $user_info->user_email) {
				$user_array[$user_info->user_email] = profile::profile_info($user_info);
			}
		}

		$company_array = array();
		foreach ($company as $key => $value) {
			$company_info = DB::row("
				SELECT *
				FROM
					" . DB::$db_prefix . "_company
				WHERE
					company_id = '".(int)$key."'
			");

			if($company_info && $company_info->company_email) {
				$company_info = company::company_info($company_info);
				foreach ($company_info->company_email as $value1) {
					if(in_array($value1, $company[$key])) {
						$company_array[$value1] = $company_info;
					}
				}
			}
		}

		/*$contact_array = array();
		$user_info = DB::row("
			SELECT *
			FROM
				" . DB::$db_prefix . "_module_contact
			WHERE
				id IN '". explode(",", $contact) ."'
		");
		foreach ($user_info as $key => $value) {
			if($value->user_email) {
				$value->user_name = get_username($value->user_firstname, $value->user_lastname);
				$contact_array[] = $value;
			}
		}*/

		if(mb_strlen(strip_tags($mail_title)) < config::app('app_min_strlen') || mb_strlen(strip_tags($mail_body)) < config::app('app_min_strlen')) {
			$error[] = twig::$lang["sendmail_error_title_body"];
		}

		// сборка адресов в один массив
        $email_array = [];
        $email_user = [];
        $email_company = [];
        $email_dop = [];
		foreach ($user_array as $key => $value) {
			if(filter_var($key, FILTER_VALIDATE_EMAIL)) {
				$email_array[] = $key;
				$email_user[] = $value->id;

				$email_user_save[$value->id][] = $key;
			}
		}
		foreach ($company_array as $key => $value) {
			if(filter_var($key, FILTER_VALIDATE_EMAIL)) {
				$email_array[] = $key;
				$email_company[] = $value->company_id;

				$email_company_save[$value->company_id][] = $key;
			}
		}
		$mail_email_dop = explode(",", $mail_email_dop);
		foreach ($mail_email_dop as $key => $value) {
			if(filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
				$email_array[] = $value;
				$email_dop[] = $value;
			}
		}

		$email_array = array_unique($email_array);
		$email_array = implode(",", $email_array);
		// сборка адресов в один массив

		if(count($email_array)<1) {
			$error[] = twig::$lang["sendmail_error_mail"];
		}

		if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		// сохранение письма
		$mail_folder = $_REQUEST['mail_draft'] ? 3 : 2;

		if($_REQUEST['mail_draft']!=1) {
			$signature_info = DB::row("
				SELECT
					*
				FROM " . DB::$db_prefix . "_sendmail_signature
				WHERE signature_owner = '".(int)$_SESSION['user_id']."' AND signature_id = '".(int)$_REQUEST['mail_signature']."'
			");

			$mail_body = $mail_body . nl2br($signature_info->signature_desc);
		}

		if($_REQUEST['mail_id']) { // обновление черновика
			$email_info = DB::row("
				SELECT *
				FROM
					" . DB::$db_prefix . "_sendmail
				WHERE
					mail_id = '".(int)$_REQUEST['mail_id']."' AND (mail_author = '".$_SESSION['user_id']."')
			");

			if($email_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_sendmail
					SET
						mail_folder = '".$mail_folder."',
						mail_title = '".addslashes(strip_tags($mail_title))."',
						mail_body = '".clear_tags(addslashes($mail_body))."',
						mail_author = '".(int)$_SESSION['user_id']."',
						mail_email = '".$email_array."',
						mail_signature = '".(int)$_REQUEST['mail_signature']."',
						mail_email_company = '". json_encode($email_company_save) ."',
						mail_email_user = '". json_encode($email_user_save) ."',
						mail_email_dop = '".implode(",", $email_dop)."',
						mail_draft = '".(($_REQUEST['mail_draft'] == 1) ? 1 : 0)."',
						mail_track = '".(($_REQUEST['mail_track'] == 1) ? 1 : 0)."',
						mail_save = '".time()."'
					WHERE
						mail_id = '".(int)$_REQUEST['mail_id']."'
				");

				$mail_id = (int)$_REQUEST['mail_id'];
				$mail_md5 = $email_info->mail_md5;
				$stop_ref=1;
			}
		} else { // сохранение черновика
			$mail_md5 = md5(microtime().$_SESSION['user_id'].$_SESSION['user_group']);

			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_sendmail
				SET
					mail_folder = '".$mail_folder."',
					mail_md5 = '".$mail_md5."',
					mail_title = '".addslashes(strip_tags($mail_title))."',
					mail_body = '".clear_tags(addslashes($mail_body))."',
					mail_author = '".(int)$_SESSION['user_id']."',
					mail_email = '".$email_array."',
					mail_signature = '".(int)$_REQUEST['mail_signature']."',
					mail_email_company = '". json_encode($email_company_save) ."',
					mail_email_user = '". json_encode($email_user_save) ."',
					mail_email_dop = '".implode(",", $email_dop)."',
					mail_draft = '".(($_REQUEST['mail_draft'] == 1) ? 1 : 0)."',
					mail_track = '".(($_REQUEST['mail_track'] == 1) ? 1 : 0)."',
					mail_save = '".time()."'
			");

			$mail_id = DB::lastInsertId();
		}
		// сохранение письма

		// отправка
		if($_REQUEST['mail_draft']!=1) {

			// отправка внутри системы пользователям
			if(is_array($user_array)) {
				foreach ($user_array as $email => $value) {
					if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$track_code = '<img src="'.HOST.'api/index.php?mail_id='.$mail_id.'&hash='.$mail_md5.'&email='.$email.'" style="height:1px;width:1px;">';
						$mail_body_tracker = ($_REQUEST['mail_track'] == 1 && $mail_folder == 2) ? $mail_body.$track_code : "";

						sendmail::send_mail(
							$email,
							$mail_body_tracker,
							$mail_title,
							$_SESSION['user_email'],
							config::app('SYS_NAME'),
							'html'
						);

						// сохранение письма в ящике внутри систему для пользователя
						DB::query("
							INSERT INTO
								" . DB::$db_prefix . "_sendmail
							SET
								mail_folder = '1',
								mail_md5 = '".$mail_md5."',
								mail_title = '".addslashes(strip_tags($mail_title))."',
								mail_body = '".clear_tags(addslashes($mail_body))."',
								mail_email = '".$email."',
								mail_author = '".$_SESSION['user_id']."',
								mail_recipient = '".$value->id."',
								mail_draft = '0',
								mail_track = '".(($_REQUEST['mail_track'] == 1) ? 1 : 0)."',
								mail_save = '".time()."'
						");
						// сохранение письма в ящике внутри систему для пользователя
					}
				}
			}
			// отправка внутри системы пользователям

			// отправка по клиентам
			if(is_array($company_array)) {
				foreach ($company_array as $email => $value) {
					if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$track_code = '<img src="'.HOST.'api/index.php?mail_id='.$mail_id.'&hash='.$mail_md5.'&email='.$email.'">';
						$mail_body_tracker = ($_REQUEST['mail_track'] == 1 && $mail_folder == 2) ? $mail_body.$track_code : "";

						sendmail::send_mail(
							$email,
							$mail_body_tracker,
							$mail_title,
							$_SESSION['user_email'],
							config::app('SYS_NAME'),
							'html'
						);
					}
				}
			}
			// отправка по клиентам

			// отправка вне системы
			if(is_array($email_dop)) {
				foreach ($email_dop as $key => $value) {
					if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$track_code = '<img src="'.HOST.'api/index.php?mail_id='.$mail_id.'&hash='.$mail_md5.'&email='.$value.'">';
						$mail_body_tracker = ($_REQUEST['mail_track'] == 1 && $mail_folder == 2) ? $mail_body.$track_code : "";

						sendmail::send_mail(
							$value,
							$mail_body_tracker,
							$mail_title,
							$_SESSION['user_email'],
							config::app('SYS_NAME'),
							'html'
						);
					}
				}
			}
			// отправка вне системы

		}
		// отправка

        $message = ($_REQUEST['mail_draft']==1) ? twig::$lang["sendmail_save_draft"] : "";
		$ref = ($stop_ref && $_REQUEST['mail_draft']) ? "" : ABS_PATH_ADMIN_LINK.'?do=sendmail&sub=open&mail_id='.$mail_id;
		echo json_encode(array("respons"=>$message, "status"=>"success", "ref"=>$ref));
		exit;
	}

	static function change_folder() {


		$mail_id = (int)$_REQUEST['mail_id'];

		$email_info = DB::row("
			SELECT *
			FROM
				" . DB::$db_prefix . "_sendmail
			WHERE
				mail_id = '".$mail_id."' AND (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."')
		");

		if($mail_id && $email_info) {
			if($_REQUEST['delete']) {
				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_sendmail
					WHERE
						mail_id = '".$mail_id."'
				");

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_sendmail_tracker
					WHERE
						track_mail_id = '".$mail_id."'
				");

				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=sendmail&folder=1');
				exit;
			}

			DB::query("
				UPDATE
					" . DB::$db_prefix . "_sendmail
				SET
					mail_folder = '4'
				WHERE
					mail_id = '".$mail_id."'
			");

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=sendmail&folder=4');
			exit;
		} else {
			access_404();
		}
	}

	static function erase_trash() {


		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_sendmail_tracker
			WHERE
				track_mail_id IN (
					SELECT mail_id FROM
						" . DB::$db_prefix . "_sendmail
					WHERE
						mail_folder = '4' AND (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."')
				)
		");

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_sendmail
			WHERE
				mail_folder = '4' AND (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."')
		");

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=sendmail&folder=4');
		exit;
	}

	static function sendmail_signature() {
		self::bild_sendmail();

		// pager
		$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
		// pager

		$signature_list = DB::fetchrow("
			SELECT
				*
			FROM " . DB::$db_prefix . "_sendmail_signature
			WHERE signature_owner = '".(int)$_SESSION['user_id']."'
			ORDER BY signature_id DESC
		");

		// pager
		$num = DB::numrows("
			SELECT
				*
			FROM " . DB::$db_prefix . "_sendmail_signature
			WHERE signature_owner = '".(int)$_SESSION['user_id']."'
		");
		twig::assign('num', $num);
		if ($num > config::app('SYS_PEAR_PAGE'))
		{
			$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=sendmail&sub=sendmail_signature&page={s}">{t}</a>');
			twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('signature_list', $signature_list);
		twig::assign('content', twig::fetch('sendmail/sendmail_signature.tpl'));
	}

	static function sendmail_signature_work() {
		$void_id = (int)$_REQUEST['void_id'];

		if($void_id) {
			$signature_info = DB::row("
				SELECT
					*
				FROM " . DB::$db_prefix . "_sendmail_signature
				WHERE signature_owner = '".(int)$_SESSION['user_id']."' AND signature_id = '".$void_id."'
			");

			if($signature_info) {
				twig::assign('signature_info', $signature_info);
			} else {
				access_404();
			}
		}

		if(isset($_REQUEST['save'])) {
			$error = [];

			$signature_desc = clear_tags($_REQUEST['signature_desc']);
			$signature_title = clear_text($_REQUEST['signature_title']);

			try {
			    v::length(config::app('app_min_strlen'), null)->assert($signature_title);
			} catch(ValidationException $exception) {
			    $error[] = twig::$lang["sendmail_signature_title_error"];
			}

			try {
			    v::length(config::app('app_min_strlen'), null)->assert($signature_desc);
			} catch(ValidationException $exception) {
			    $error[] = twig::$lang["sendmail_signature_desc_error"];
			}

			if($error) {
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if($signature_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_sendmail_signature
					SET
						signature_title = '".$signature_title."',
						signature_desc = '".$signature_desc."'
					WHERE
						signature_owner = '".(int)$_SESSION['user_id']."' AND signature_id = '".$void_id."'
				");

				echo json_encode(array("status"=>"success", "respons"=>twig::$lang["form_save_success"]));
			} else {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_sendmail_signature
					SET
						signature_title = '".$signature_title."',
						signature_desc = '".$signature_desc."',
						signature_owner = '".(int)$_SESSION['user_id']."'
				");

				echo json_encode(array("ref"=>ABS_PATH_ADMIN_LINK.'?do=sendmail&sub=sendmail_signature', "respons"=>twig::$lang["form_save_success"]));
			}
			exit;
		}

		$title = ($signature_info) ? twig::$lang["sendmail_signature_edit"] : twig::$lang["sendmail_signature_add"];

		$html = twig::fetch('sendmail/sendmail_signature_work.tpl');
		echo json_encode(array("title"=>$title, "html"=>$html, "status"=>"success"));
		exit;
	}

	static function sendmail_signature_delete() {
		$signature_id = (int)$_REQUEST['signature_id'];

		if($signature_id) {
			$signature_info = DB::row("
				SELECT
					*
				FROM " . DB::$db_prefix . "_sendmail_signature
				WHERE signature_owner = '".(int)$_SESSION['user_id']."' AND signature_id = '".$signature_id."'
			");

			if($signature_info) {

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_sendmail_signature
					WHERE
						signature_owner = '".(int)$_SESSION['user_id']."' AND signature_id = '".$signature_id."'
				");

				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=sendmail&sub=sendmail_signature');
		    	exit;
			}
		}
	}

	static function mass_change() {


    	$elem_opt = $_REQUEST['elem_opt'];
    	$mass_do = $_REQUEST['operation'];

    	if(count($elem_opt) >= 1) {
    		switch ($mass_do) {

				case '1':

    				foreach ($elem_opt as $key => $value) {

    					DB::query("
							UPDATE
								" . DB::$db_prefix . "_sendmail
							SET
								mail_open = '".time()."'
							WHERE mail_id = '".(int)$value."' AND mail_recipient = '".$_SESSION['user_id']."' AND mail_folder = '1'
						");

	    			}

    			break;

				case '2':

    				foreach ($elem_opt as $key => $value) {

    					DB::query("
							UPDATE
								" . DB::$db_prefix . "_sendmail
							SET
								mail_open = '0'
							WHERE mail_id = '".(int)$value."' AND mail_recipient = '".$_SESSION['user_id']."' AND mail_folder = '1'
						");

	    			}

    			break;

    			case '3':

	    			foreach ($elem_opt as $key => $value) {

	    				$mail_info = DB::row("
							SELECT mail_id
							FROM " . DB::$db_prefix . "_sendmail
							WHERE (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."') AND mail_id = '".(int)$value."'
						");

	    				if($mail_info) {
	    					DB::query("
								DELETE FROM
								" . DB::$db_prefix . "_sendmail
								WHERE mail_id = '".(int)$value."'
							");

							DB::query("
								DELETE FROM
									" . DB::$db_prefix . "_sendmail_tracker
								WHERE
									track_mail_id = '".(int)$value."'
							");
	    				}
	    			}

    			break;

    			case '4':

    				foreach ($elem_opt as $key => $value) {

	    				$mail_info = DB::row("
							SELECT mail_id
							FROM " . DB::$db_prefix . "_sendmail
							WHERE (mail_author = '".$_SESSION['user_id']."' OR mail_recipient = '".$_SESSION['user_id']."') AND mail_id = '".(int)$value."'
						");

	    				if($mail_info) {
	    					DB::query("
								UPDATE
									" . DB::$db_prefix . "_sendmail
								SET
									mail_folder = '4'
								WHERE mail_id = '".$mail_info->mail_id."'
							");
	    				}
	    			}

    			break;


    		}
    	}

    	$folder = (int)$_REQUEST['folder'];
    	$folder = (array_key_exists($folder, self::$mail_folder)) ? "&folder=".$folder : "";

    	header('Location:'.ABS_PATH_ADMIN_LINK.'?do=sendmail'.$folder);
    	exit;
    }

  	static function mail_stat () {


		self::bild_sendmail();

  		$mail_id = (int)$_REQUEST['mail_id'];
		$main_users = get_work_user();

  		$email_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_sendmail
			WHERE mail_author = '".$_SESSION['user_id']."' AND mail_id = '".$mail_id."'
		");

		if($email_info && $email_info->mail_folder == 2 && $email_info->mail_track == 1) {
			$email_info->mail_email = $email_info->mail_email ? explode(",", $email_info->mail_email) : "";
			$email_info->mail_author = $main_users[$email_info->mail_author];
			$email_info->mail_recipient = $main_users[$email_info->mail_recipient];
			$email_info->mail_save = unix_to_date($email_info->mail_save);
			twig::assign('email_info', $email_info);

			// eff
			$email_info->count_email = count($email_info->mail_email);
			$email_info->count_email_open = DB::numrows("
				SELECT
				track_mail_id
				FROM " . DB::$db_prefix . "_sendmail_tracker
				WHERE track_mail_id = '".$mail_id."'
				GROUP BY track_email
			");
			$email_info->count_email_conv = format_string_number(($email_info->count_email_open/$email_info->count_email)*100);
			// eff

			$day_array_temp = array();
			$end_time = time();
			for ($i=$end_time-604800; $i <= $end_time; $i++) {
				$i = $i+86400;
				$day_array_temp[] = date('d.m.Y', $i);
			}
			$sql = DB::fetchrow("
				SELECT
					DATE_FORMAT(FROM_UNIXTIME(track_view_date),'%d.%m.%Y') as period,
					COUNT(track_id) as count_track
				FROM " . DB::$db_prefix . "_sendmail_tracker
				WHERE
					track_view_date BETWEEN '".($end_time-604800)."' AND '".$end_time."' AND track_mail_id = '".$mail_id."'
				GROUP BY period
				ORDER BY period DESC
				LIMIT 7
			");
			$track_day = array();
			$track_count = array();
			foreach ($sql as $row) {
				$track_day[$row->period] = "'".$row->period."'";
				$track_count[$row->period] = $row->count_track;
			}

			foreach ($day_array_temp as $key => $value) {
				$period[] = "'".$value."'";
				$count[$value] = (($track_count[$value]) ? $track_count[$value] : 0);
			}
			$period = implode(",", $period);
			$count = implode(",", $count);
			twig::assign('period', $period);
			twig::assign('count', $count);


			// pager
			$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
			// pager

			$tracker = DB::fetchrow("
				SELECT
				*
				FROM " . DB::$db_prefix . "_sendmail_tracker
				WHERE track_mail_id = '".$mail_id."'
				ORDER BY track_view_date DESC
				LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE') . "
			");
			foreach ($tracker as $row) {
				$row->track_view_date = unix_to_date($row->track_view_date);
			}
			twig::assign('tracker', $tracker);

			// pager
			$num = DB::numrows("
				SELECT
				track_mail_id
				FROM " . DB::$db_prefix . "_sendmail_tracker
				WHERE track_mail_id = '".$mail_id."'
			");
			if ($num > config::app('SYS_PEAR_PAGE'))
			{
				$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=sendmail&sub=mail_stat&mail_id='.$mail_id.'&page={s}">{t}</a>');
				twig::assign('page_nav', $page_nav);
			}
			// pager
		} else {
			access_404();
		}

  		twig::assign('content', twig::fetch('sendmail/sendmail_stat.tpl'));
  	}

  	// сохранение писем в ящике
  	static function send_mail_to_box($mail_title, $mail_body, $mail_recipient, $mail_email, $mail_folder=1, $mail_label=1, $mail_tracker=1) {
		self::bild_sendmail();

        if(($mail_folder && !array_key_exists($mail_folder, self::$mail_folder)) || ($mail_label && !array_key_exists($mail_label, self::$mail_label))) {
            return false;
        }

        $mail_md5 = md5(microtime().$mail_recipient);

        DB::query("
            INSERT INTO
                " . DB::$db_prefix . "_sendmail
            SET
                mail_folder = '".$mail_folder."',
                mail_md5 = '".$mail_md5."',
                mail_label = '".$mail_label."',
                mail_title = '".addslashes($mail_title)."',
                mail_body = '".addslashes($mail_body)."',
                mail_author = '0',
                mail_email = '".$mail_email."',
                mail_recipient = '".$mail_recipient."',
                mail_draft = '0',
                mail_track = '".(($mail_tracker == 1) ? 1 : 0)."',
                mail_save = '".time()."'
        ");
        //$mail_id = DB::lastInsertId();
        return $mail_body;
    }
    // сохранение писем в ящике

    // трекер
    static function tracker() {
        $mail_id = (int)$_REQUEST['mail_id'];
        $hash = addslashes($_REQUEST['hash']);
        $email = addslashes($_REQUEST['email']);

        $email_info = DB::row("
            SELECT *
            FROM
                " . DB::$db_prefix . "_sendmail
            WHERE
                mail_id = '".$mail_id."' AND mail_md5 = '".$hash."' AND (mail_folder = '2' OR mail_folder = '1') AND (mail_email LIKE '%".$email."%' OR mail_email_dop LIKE '%".$email."%')
        ");
        if($email_info) {
            DB::query("
                INSERT INTO
                    " . DB::$db_prefix . "_sendmail_tracker
                SET
                    track_mail_id = '".$mail_id."',
                    track_view_date = '".time()."',
                    track_view_ip = '".addslashes($_SERVER['REMOTE_ADDR'])."',
                    track_email = '".$email."'
            ");

            header('Content-Type: image/gif');
            echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
        }
        exit;
    }
    // трекер

	// билдер отправки сообщений из системы
	static function sendmail_bilder($profile_info, $subject, $email_body, $no_need_check_send=false, $save_to_inbox = true) {
		if($profile_info->user_get_login == 1 || $no_need_check_send === true) {
			sendmail::send_mail(
				$profile_info->user_email,
				$email_body,
				$subject,
				config::app('SYS_MAIL'),
				config::app('SYS_MAIL_SENDER'),
				'html'
			);
		}

		if($save_to_inbox === true) {
			sendmail::send_mail_to_box($subject, $email_body, $profile_info->id, $profile_info->user_email);
		}
	}
	// билдер отправки сообщений из системы

    static function str_nospace($string)
	{
		return trim(str_replace(array(' ',"\r","\n","\t"),'',$string));
	}
	static function send_mail($to='',$body='',$subject='',$from_email='',$from_name='',$type='html',$attach=array(),$saveattach=true)
	{
		if(!filter_var($to, FILTER_VALIDATE_EMAIL)) return false;

		ob_start();
		require_once BASE_DIR . '/vendor/SwiftMailer2/vendor/autoload.php';
		unset($transport,$message,$mailer);
		$to = sendmail::str_nospace($to);
		$from_email = sendmail::str_nospace($from_email);

		// Определяем тип письма
		$type = ((strtolower($type) == 'html' || strtolower($type) == 'text/html') ? 'text/html' : 'text/plain');
		// Добавляем подпись, если просили
		$signature = 1;
		if ($signature)
		{
			if ($type == 'text/html')
			{
				$signature = '<br>' . nl2br(config::app('SYS_MAIL_SIG'));
			}
			else
			{
				$signature = "\r\n" . config::app('SYS_MAIL_SIG');
			}
		} else {
			$signature = '';
		}

		// Составляем тело письма
		$body = stripslashes($body);
		twig::assign('subject', $subject);
		twig::assign('signature', $signature);
		twig::assign('body', $body);
		$body = twig::fetch('mail/mail_template.tpl');

		// Формируем письмо
		$message = Swift_Message::newInstance($subject)
			-> setFrom(array($from_email => $from_name))
			-> setTo($to)
			-> setContentType($type)
			-> setBody($body);
		// Прикрепляем вложения
		if ($attach)
		{
			foreach ($attach as $attach_file)
			{
				$message->attach(Swift_Attachment::fromPath(trim($attach_file)));
			}
		}
		// Выбираем метод отправки и формируем транспорт
		$transport = Swift_MailTransport::newInstance();
		// Сохраняем вложения в $attach_dir, если просили
		if ($attach && $saveattach)
		{
			$attach_dir = BASE_DIR . '/' . config::app('app_attach_dir') . '/';
			foreach ($attach as $file_path)
			{
				if ($file_path && file_exists($file_path))
				{
					$file_name = basename($file_path);
					$file_name = str_replace(' ','',mb_strtolower(trim($file_name)));
					if (file_exists($attach_dir . $file_name))
					{
						$file_name = rand(1000, 9999) . '_' . $file_name;
					}
					$file_path_new = $attach_dir . $file_name;
					if (!@move_uploaded_file($file_path,$file_path_new))
					{
						copy($file_path,$file_path_new);
					}
				}
			}
		}
		// Отправляем письмо
		$mailer = Swift_Mailer::newInstance($transport);
		if (!@$mailer->send($message, $failures))
		{
			logs::add(twig::$lang['send_mail_false'] . implode(',',$failures) . "<br><br>" . $body, 1);
			return $failures;
		}
	    return true;
	}
}
?>
