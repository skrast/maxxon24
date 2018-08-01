<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class logs {
	static $log_setting = 1;
	static $main_title =  array();

	static $log_type = "";

	function __construct() {
		twig::assign('app_log_delete', config::app('app_log_delete'));
		twig::assign('app_error_delete', config::app('app_error_delete'));

		self::$log_type =  twig::$lang["log_type"];
		twig::assign('log_type', self::$log_type);
	}

	static function validation_permission() {
		try {
			v::key('alles')->assert($_SESSION);
		} catch(ValidationException $exception) {
			return false;
		}
		return true;
	}

	static function check_permission() {
		if(self::validation_permission() === false) {
			access_404();
		}
	}

	static function bild_filter() {
		$page_link = (isset($_REQUEST['filter'])) ? "&filter=1" : "";
		$sql_link = "";
        $start_time = "";
        $end_time = "";

		$main_users = get_work_user();

		if(!empty($_REQUEST['start'])) {
			$start_time = date_to_unix(urldecode($_REQUEST['start']));
			twig::assign('start', date('d.m.Y H:i', $start_time));

			$sql_link .= "AND log_time >= '".$start_time."' ";
			$page_link .= "&start=".urlencode(date('d.m.Y H:i', $start_time));
		}
		if(!empty($_REQUEST['end'])) {
			$end_time = date_to_unix(urldecode($_REQUEST['end']));
			twig::assign('end', date('d.m.Y H:i', $end_time));

			$sql_link .= "AND log_time <= '".$end_time."' ";
			$page_link .= "&end=".urlencode(date('d.m.Y H:i', $end_time));
		}
		if(!empty($_REQUEST['owner_id']) && array_key_exists($_REQUEST['owner_id'], $main_users)) {
			$page_link .= "&owner_id=".(int)$_REQUEST['owner_id'];
			$sql_link .= "AND log_user_id = '".(int)$_REQUEST['owner_id']."' ";
		}

		if(isset($_REQUEST['log_type']) && array_key_exists($_REQUEST['log_type'], self::$log_type)) {
			$sql_link .= " AND log_rubric = '".(int)$_REQUEST['log_type']."' ";
			$page_link .= "&log_type=".(int)$_REQUEST['log_type'];
		}

		if(isset($_REQUEST['search_ip'])) {
			$sql_link .= " AND log_ip LIKE '".addslashes(trim($_REQUEST['search_ip']))."%' ";
			$page_link .= "&search_ip=".urlencode(addslashes($_REQUEST['search_ip']));
		}

		return array($page_link, $sql_link);
	}

	static function bild_page() {
		self::check_permission();

		// сборка параметров для поиска
		$bild_filter = self::bild_filter();
		$page_link = $bild_filter[0];
		$sql_link = $bild_filter[1];
		// сборка параметров для поиска

		self::$main_title[] =  twig::$lang["log_title"];

		/* пользователи */
		$main_users = get_work_user();
		twig::assign('main_users', $main_users);
		/* пользователи */

		// динамика активности
		$end_time = time();
		$day_array_temp = array();
		for ($i=$end_time-604800*2; $i <= $end_time; $i++) {
			$i = $i+86400;
			$day_array_temp[] = date('d.m.Y', $i);
		}

		$sql = DB::fetchrow("
			SELECT
				DATE_FORMAT(FROM_UNIXTIME(log_time),'%d.%m.%Y') as period,
				COUNT(log_time) AS sum
			FROM " . DB::$db_prefix . "_logs
			WHERE log_type = '0'
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(log_time),'%d.%m.%Y')
			ORDER BY log_time DESC
			LIMIT 14
		");
        $check_sum = array();
        $period = array();
		foreach ($sql as $row) {
			$check_sum[$row->period] = $row->sum;
		}
		foreach ($day_array_temp as $key => $value) {
			$period[] = $value;
			$sum[] = (isset($check_sum[$value])) ? $check_sum[$value] : 0;
		}
		twig::assign('sum', json_encode($sum));
		twig::assign('period', json_encode($period));
		// динамика активности

		// pager
		$start = get_current_page() * config::app('SYS_PEAR_PAGE_LOG') - config::app('SYS_PEAR_PAGE_LOG');
		// pager

		$logs = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_logs as log
			WHERE log_type = '0' $sql_link
			ORDER BY log_time DESC
			LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE_LOG') . "
		");
		foreach ($logs as $row) {
			$row->log_time = unix_to_date($row->log_time);
			if($row->log_user_id) $row->log_user = $main_users[$row->log_user_id];
		}
		twig::assign('logs', $logs);

		// pager
		$num = DB::numrows("
			SELECT log_time FROM
				" . DB::$db_prefix . "_logs
			WHERE log_type = '0' $sql_link
		");
		twig::assign('num', $num);
		if ($num > config::app('SYS_PEAR_PAGE_LOG'))
		{
			$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE_LOG')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=logs'.$page_link.'&page={s}">{t}</a>');
			twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('page_link', $page_link);
		twig::assign('content', twig::fetch('logs.tpl'));
	}

	static function error() {
		self::check_permission();

		// сборка параметров для поиска
		$bild_filter = self::bild_filter();
		$page_link = $bild_filter[0];
		$sql_link = $bild_filter[1];
		// сборка параметров для поиска

		self::$main_title[] =  twig::$lang["log_title2"];

		/* пользователи */
		$main_users = get_work_user();
		twig::assign('main_users', $main_users);
		/* пользователи */

		// динамика активности
		$end_time = time();
		$day_array_temp = array();
		for ($i=$end_time-604800*2; $i <= $end_time; $i++) {
			$i = $i+86400;
			$day_array_temp[] = date('d.m.Y', $i);
		}

		$sql = DB::fetchrow("
			SELECT
				DATE_FORMAT(FROM_UNIXTIME(log_time),'%d.%m.%Y') as period,
				COUNT(log_time) AS sum
			FROM " . DB::$db_prefix . "_logs
			WHERE log_type = '1'
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(log_time),'%d.%m.%Y')
			ORDER BY log_time DESC
			LIMIT 14
		");
        $check_sum = array();
        $period = array();
		foreach ($sql as $row) {
			$check_sum[$row->period] = $row->sum;
		}
		foreach ($day_array_temp as $key => $value) {
			$period[] = $value;
			$sum[] = (isset($check_sum[$value])) ? $check_sum[$value] : 0;
		}
		twig::assign('sum', json_encode($sum));
		twig::assign('period', json_encode($period));
		// динамика активности

		// pager
		$start = get_current_page() * config::app('SYS_PEAR_PAGE_LOG') - config::app('SYS_PEAR_PAGE_LOG');
		// pager

		$logs = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_logs as log
			WHERE log_type = '1' $sql_link
			ORDER BY log_time DESC
			LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE_LOG') . "
		");
		foreach ($logs as $row) {
			$row->log_time = unix_to_date($row->log_time);
			if($row->log_user_id) $row->log_user = $main_users[$row->log_user_id];
		}
		twig::assign('logs', $logs);

		// pager
		$num = DB::numrows("
			SELECT log_time FROM
				" . DB::$db_prefix . "_logs
			WHERE log_type = '1' $sql_link
		");
		twig::assign('num', $num);
		if ($num > config::app('SYS_PEAR_PAGE_LOG'))
		{
			$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE_LOG')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=logs&sub=error'.$page_link.'&page={s}">{t}</a>');
			twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('page_link', $page_link);
		twig::assign('content', twig::fetch('error.tpl'));
	}

	static function clear() {
		self::check_permission();

		if(config::app('app_log_delete')) {
			DB::query("DELETE FROM " . DB::$db_prefix . "_logs	WHERE log_type = '0'");
			logs::add(twig::$lang['log_delete_log'], self::$log_setting);
		}
		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=logs');
        exit;
	}
	static function clear_error() {
		self::check_permission();

		if(config::app('app_error_delete')) {
			DB::query("DELETE FROM " . DB::$db_prefix . "_logs	WHERE log_type = '1' ");
			logs::add(twig::$lang['log_delete_error'], self::$log_setting);
		}
		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=logs&sub=error');
        exit;
	}

	static function add($message, $rub = 1, $type = 0) {
		DB::query("
			INSERT INTO " . DB::$db_prefix . "_logs
			SET
				log_time = '".time()."',
				log_ip = '".addslashes($_SERVER['REMOTE_ADDR'])."',
				log_url = '".addslashes($_SERVER['QUERY_STRING'])."',
				log_text = '".clear_text($message,'<br>')."',
				log_rubric = '".(int)$rub."',
				log_type = '".$type."',
				log_status = '0',
				log_user_id = '".(int)$_SESSION['user_id']."'
		");

		return true;
	}
}
?>
