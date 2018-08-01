<?php
class auth {
	static $log_setting = 1;

    public function __construct() {

    }

	// login
    public static function user_login($login = "", $password = "") {
		global $cookie_domain;
		sleep(1);
	    $time = time();

		if (empty($login)) {
			logs::add(twig::$lang['login_empty'], self::$log_setting);
			return false;
		}

		$password =  htmlspecialchars($password);
		$login =  htmlspecialchars($login);

		$row = DB::row("
			SELECT
				usr.*,
				grp.user_group_permission,
				grp.user_group_module,
				grp.user_group_name
			FROM " . DB::$db_prefix . "_users AS usr
			JOIN " . DB::$db_prefix . "_users_groups AS grp ON grp.user_group = usr.user_group
			WHERE UPPER(user_email) = UPPER('" . addslashes($login) . "')
			LIMIT 1
		");

		if (! (isset($row->user_password) && password_verify($password, $row->user_password))) {
			logs::add(twig::$lang['login_error'] . ' - '
					        			. stripslashes($_REQUEST['user_email']) . ' / '
					        			. stripslashes($_REQUEST['user_pass']), self::$log_setting);

			return false;
		}

		if ($row->user_status != 1) return $row;

		// сохранение добавленных документов
		$user_writer = $_SESSION['user_writer'];

		session::close_session();

		$hash = password_hash($password, PASSWORD_DEFAULT);

		auth::set_auth_param($row, $hash);

		$expire = $time + config::app('COOKIE_LIFETIME');
		$auth = md5($_SERVER['HTTP_USER_AGENT'].md5($row->id));
		$sql = "DELETE FROM " . DB::$db_prefix . "_users_session WHERE `hash`='" . addslashes($auth) . "'";
		DB::query($sql);
		$sql = "INSERT INTO " . DB::$db_prefix . "_users_session (`user_id`,`hash`,`ip`,`agent`,`last_activ`) values ('" . $row->id . "','" . addslashes($auth) . "','','" . addslashes($_SERVER['HTTP_USER_AGENT']) . "','" . time() . "')";
		DB::query($sql);
		@setcookie('auth', $auth, $expire, session::$cookie_path, $cookie_domain, 0, 1);

		logs::add(twig::$lang['login_start'], self::$log_setting);

		// сохранение добавленных документов
		if($user_writer) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_writer
				SET
					document_owner = '".$_SESSION['user_id']."'
				WHERE document_id IN (".implode(",", $user_writer).") AND document_owner = '0'
			");

			$moder = DB::row("SELECT * FROM " . DB::$db_prefix . "_users_writer WHERE user_group = '2' AND user_status = '1' ORDER BY user_visittime DESC LIMIT 1");

			// сообщения о заказе
			foreach ($user_writer as $row) {
				$message = twig::$lang['message_user_writer_add'] . " ID №".$row;
				siteMessage::message_add($_SESSION['user_id'], $moder->id, $message);
			}
			// сообщения о заказе
		}

		return true;
	}

	// устанавливаем нужные значения в сессию
	static function set_auth_param($row, $hash) {
		if(empty($row)) return false;

		$u_ip = "INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')" ;
		DB::query("
			UPDATE " . DB::$db_prefix . "_users
			SET
				user_visittime = '" . time() . "',
				user_password   = '" . $hash . "',
				user_ip    =  " . $u_ip . "
			WHERE
				id = '" . $row->id . "'
		");

		$row->user_login = $row->user_login ? $row->user_login : 'No name';

		$_SESSION['user_id']       = $row->id;
		$_SESSION['user_use_notify']   = $row->user_use_notify;
		$_SESSION['user_password']     = $hash;
		$_SESSION['user_photo']     = $row->user_photo ? $row->user_photo : "no-avatar.png";
		$_SESSION['user_group']    = $row->user_group;
		$_SESSION['user_subgroup']    = $row->user_type_form;
		$_SESSION['user_billing']    = $row->user_billing;
		$_SESSION['user_billing_price']    = $row->user_billing_price;
		$_SESSION['user_phone']    = $row->user_phone;
		$_SESSION['user_email']    = $row->user_email;
		$_SESSION['user_login']    = $row->user_login;
		$_SESSION['user_firstname']    = $row->user_firstname;
		$_SESSION['user_lastname']    = $row->user_lastname;
		$_SESSION['user_group_name']    = $row->user_group_name;
		$_SESSION['user_name']    = get_username($row->user_firstname, $row->user_lastname, $row->user_login);
		$_SESSION['user_ip']       = addslashes($_SERVER['REMOTE_ADDR']);
		$_SESSION['user_lang']    = config::get_user_lang($row->user_lang);
		$_SESSION['full_user_id']    = profile::bild_full_user_id($row);

		if($_SESSION['user_group'] == 3) {
			$_SESSION['user_work']    = DB::numrows("
				SELECT order_id FROM " . DB::$db_prefix . "_users_order
				WHERE order_perfomens = '". (int)$row->id ."' AND order_accept = '1'
			");
		}

		if($_SESSION['user_group'] == 4) {
			$_SESSION['user_work'] = DB::numrows("
				SELECT order_id FROM " . DB::$db_prefix . "_users_order
				WHERE order_owner = '". (int)$row->id ."' AND order_status != '0' AND order_delete != '1'
			");
		}

		if(is_array($row->user_project)) {
			foreach ($row->user_project as $key => $value) {
				if($value) {
					$allow[] = (int)$value;
				}
			}
		} else {
			$explode = explode(",", $row->user_project);
			foreach ($explode as $key => $value) {
				if($value) {
					$allow[] = (int)$value;
				}
			}
		}
		if(is_array($allow)) {
			$_SESSION['user_project'] = $allow;
			$_SESSION['user_show_project'] = $allow;
		} else {
			$_SESSION['user_project'] = "";
			$_SESSION['user_show_project'] = "";
		}

		/*$_SESSION['user_project'] = (is_array($row->user_project)) ? $row->user_project : explode(",", $row->user_project);
		$_SESSION['user_show_project'] = (is_array($row->user_project)) ? $row->user_project : explode(",", $row->user_project);*/

		// настройки для группы
		$user_group_permissions = explode('|', preg_replace('/\s+/', '', $row->user_group_permission));
		foreach ($user_group_permissions as $user_group_permission) {
			$_SESSION[$user_group_permission] = 1;
		}
		// настройки для группы

		// настройки для пользователя
		$user_permissions = explode('|', preg_replace('/\s+/', '', $row->user_permissions));
		foreach ($user_permissions as $user_permission) {
			$_SESSION[$user_permission] = 1;
		}
		// настройки для пользователя

		// доступ к модулям
		$user_group_module = explode(',', preg_replace('/\s+/', '', $row->user_group_module));
		$_SESSION['user_group_module'] = [];
		foreach ($user_group_module as $group_module) {
			$_SESSION['user_group_module'][] = $group_module;
		}
		// доступ к модулям

		if($row->user_timezone) {
			$curent_zone = DB::row("
				SELECT z.country_code, z.zone_name, tz.abbreviation, tz.gmt_offset, tz.dst
				FROM " . DB::$db_prefix . "_timezone_time tz
				JOIN " . DB::$db_prefix . "_timezone_zone z ON tz.zone_id=z.zone_id
				WHERE z.zone_id='".$row->user_timezone."'
				ORDER BY tz.time_start DESC LIMIT 1;
			", config::app('CACHE_LIFETIME_LONG'));
			if($curent_zone) {
				$curent_zone->gmt_offset = $curent_zone->gmt_offset/3600;
				$_SESSION['user_timezone']    = $curent_zone->gmt_offset;
			}
		}

		$u_ip = "INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')" ;
		DB::query("
			UPDATE " . DB::$db_prefix . "_users
			SET
				user_visittime = '" . time() . "',
				user_ip    =  " . $u_ip . "
			WHERE
				id = '" . $row->id . "'
		");

        return true;
	}
	// устанавливаем нужные значения в сессию

	// проверка сессии
    public static function auth_sessions() {

		if (empty($_SESSION['user_id']) || empty($_SESSION['user_password'])) return false;
		$referer = false;
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$referer = parse_url($_SERVER['HTTP_REFERER']);
			$referer = (trim($referer['host']) === $_SERVER['SERVER_NAME']);
		}
		// Если не наш REFERER или изменился IP-адрес
		// сверяем данные сессии с данными базы данных
		if ($referer === false || $_SESSION['user_ip'] != $_SERVER['REMOTE_ADDR'])
		{
			$verified = DB::numrows("
				SELECT 1
				FROM " . DB::$db_prefix . "_users
				WHERE id = '" . (int)$_SESSION['user_id'] . "'
				AND user_password = '" . addslashes($_SESSION['user_password']) . "'  AND user_status = '1'
				LIMIT 1
			");

			if (!$verified) return false;
			$_SESSION['user_ip'] = addslashes($_SERVER['REMOTE_ADDR']);
		}
		return true;
	}

	// авторизация по кукисам
    public static function auth_cookie() {
		global $cookie_domain;

		if (empty($_COOKIE['auth'])) {
			return false;
		}

		$sql = "
			SELECT user_id FROM
			" . DB::$db_prefix . "_users_session
			WHERE hash = '".addslashes($_COOKIE['auth'])."'
		";
		$user_id = DB::single($sql);

		if ((int)$user_id == 0){
			@setcookie('auth', '', 0, session::$cookie_path, $cookie_domain, 0, 1);
			return false;
		}
		$row = DB::row("
			SELECT
				usr.*,
				usrs.ip,
				grp.user_group_permission,
				grp.user_group_name
			FROM " . DB::$db_prefix . "_users AS usr
			JOIN " . DB::$db_prefix . "_users_groups AS grp ON grp.user_group = usr.user_group
			JOIN " . DB::$db_prefix . "_users_session AS usrs ON usr.Id = usrs.user_id
			WHERE usr.id = '" . $user_id . "' AND usrs.hash = '".$_COOKIE['auth']."'  AND usr.user_status = '1'
			LIMIT 1
		");

		if (empty($row)) return false;
		$row->ip = long2ip($row->ip);

		DB::query("
			UPDATE " . DB::$db_prefix . "_users_session
			SET
				last_activ = '" . time() . "',
				ip    =  '" . ip2long($_SERVER['REMOTE_ADDR']) . "'
			WHERE
				id = '" . $row->id . "'
		");

		auth::set_auth_param($row, $row->user_password);

		$time = time();
		$expire = $time + config::app('COOKIE_LIFETIME');
		$auth = md5($_SERVER['HTTP_USER_AGENT'].md5($row->id));
		@setcookie('auth', $auth, $expire, session::$cookie_path, $cookie_domain, 0, 1);

		return true;
	}

	// проверка на бан
	public static function check_user_ban() {
		if(time() > ($_SESSION['check_ban']+config::app('app_session')) && $_SESSION['user_group']) {
			$user_info = DB::row("
				SELECT id
				FROM " . DB::$db_prefix . "_users
				WHERE
					id = '" . (int)$_SESSION['user_id'] . "' AND
					user_password = '" . addslashes($_SESSION['user_password']) . "'  AND
					user_status = '1'
				LIMIT 1
			");

			if (!$user_info)
			{
				// чистим данные авторизации в сессии
				unset($_SESSION['user_id'], $_SESSION['user_pass'], $_SESSION['user_group']);
				// чистим данные авторизации в сессии
			} else {
				$_SESSION['check_ban'] = time();
			}
		}
	}
	// проверка на бан

	// проверка на бан
	static function check_ban($user_email, $ip) {
		DB::query("
			DELETE FROM " . DB::$db_prefix . "_fail2ban
			WHERE
				expiry <= ".time()."
		");

		$count = DB::numrows("
			SELECT ip
			FROM " . DB::$db_prefix . "_fail2ban
			WHERE
				ip = '" . addslashes($ip) . "' AND
				login = '" . addslashes($user_email) . "'
		");

		return (
			$count < config::app('app_login_max_error')
		) ? false : true;
	}

	static function make_ban($user_email, $ip) {
		$ban_time = time()+config::app('app_login_ban_time');

		DB::query("
			INSERT INTO
				" . DB::$db_prefix . "_fail2ban
			SET
				ip = '" . addslashes($ip) . "',
				login = '" . addslashes($user_email) . "',
				expiry = '".$ban_time."'
		");

		$count = DB::numrows("
			SELECT ip
			FROM " . DB::$db_prefix . "_fail2ban
			WHERE
				ip = '" . addslashes($ip) . "' AND
				login = '" . addslashes($user_email) . "'
		");

		return $count;
	}
	// проверка на бан

	// выход из системы
	static function logout() {
        session::close_session();

		header('Location:auth.php');
		exit;
	}
	// выход из системы
}
?>
