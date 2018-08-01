<?php
class removeSession {
	static $desc = "Удаление старых сессий";
	static $period = 86400;
	static $group = 1;

	static function run_task() {

		set_time_limit(9999999);

		switch (config::app('app_type_session')) {
			case 'file':
				$strategy = new sessionFile();
			break;

			case 'redis':
				$strategy = new sessionRedis();
			break;

			case 'db':
			default:
				$strategy = new sessionDB();
			break;
		}

		$strategy::delete_session();

		echo __CLASS__ . ": success<br>";
		return true;
	}
}
?>
