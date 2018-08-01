<?php
class session {
	static $sess_lifetime;
	static $cookie_path;
	static $strategy;

	static function work() {
		self::$sess_lifetime = (config::app('SESSION_LIFETIME') && is_numeric(config::app('SESSION_LIFETIME')))
			? config::app('SESSION_LIFETIME')
			: (get_cfg_var("session.gc_maxlifetime") < 1440 ? 1440 : get_cfg_var("session.gc_maxlifetime"));

		self::$cookie_path = "/";

		self::set_cookie_domain();

		switch (config::app('app_type_session')) {
			case 'file':
				self::$strategy = new sessionFile();
			break;

			case 'redis':
				self::$strategy = new sessionRedis();
			break;

			case 'db':
			default:
				self::$strategy = new sessionDB();
			break;
		}

		self::$strategy::work(self::$strategy);

		session_start();
	}

	static function delete_session() {
		self::$strategy::delete_session();
	}

	static function set_cookie_domain($cookie_domain = '')
	{
		global $cookie_domain;

		if ($cookie_domain == '' && defined('COOKIE_DOMAIN') && COOKIE_DOMAIN != '')
		{
			$cookie_domain = COOKIE_DOMAIN;
		}
		elseif ($cookie_domain == '' && !empty($_SERVER['HTTP_HOST']))
		{
			$cookie_domain = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES);
		}

		// Удаляем ведущие www. и номер порта в имени домена для использования в cookie.
		$cookie_domain = ltrim($cookie_domain, '.');
		if (strpos($cookie_domain, 'www.') === 0)
		{
			$cookie_domain = substr($cookie_domain, 4);
		}
		$cookie_domain = explode(':', $cookie_domain);
		$cookie_domain = '.'. $cookie_domain[0];

		// В соответствии с RFC 2109, имя домена для cookie должно быть второго или более уровня.
		// Для хостов 'localhost' или указанных IP-адресом имя домена для cookie не устанавливается.
		if (count(explode('.', $cookie_domain)) > 2 && !is_numeric(str_replace('.', '', $cookie_domain)))
		{
			ini_set('session.cookie_domain', $cookie_domain);
		}

		ini_set('arg_separator.output',     '&amp;');
		ini_set('session.name', config::app('app_name'));
		ini_set('session.cache_limiter',    'none');
		ini_set('session.cookie_lifetime',  60*60*24*14);
		ini_set('session.gc_maxlifetime',   60*24);
		ini_set('session.use_cookies',      1);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.use_trans_sid',    0);
		ini_set('session.save_handler', 'user');
		ini_set('session.save_path', BASE_DIR . '/' . config::app('app_session_dir') . '/');
		ini_set('session.cookie_path', self::$cookie_path);
	}

	// очистка сессии
	static function close_session() {
		global $cookie_domain;

		// unset session
		@setcookie('auth', '', 0, ABS_PATH, $cookie_domain, 0, 1);

		if($_SESSION['user_id']) {
			DB::query("
				DELETE FROM
					" . DB::$db_prefix . "_users_session
				WHERE
					user_id = '".$_SESSION['user_id']."'
			");
		}
		@session_destroy();
		session_unset();
		$_SESSION = array();
		$_COOKIE = array();
		// unset session

		session::work();
	}
	// очистка сессии
}
?>
