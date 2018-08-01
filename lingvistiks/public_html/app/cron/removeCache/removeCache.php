<?php
class removeCache {
	static $desc = "Удаление кеша запросов";
	static $period = 86400;
	static $group = 1;

	static function run_task() {

		set_time_limit(9999999);

		switch (config::app('app_type_cache')) {
			case 'redis':
				$strategy = new cacheRedis();
			break;

			case 'file':
			default:
				$strategy = new cacheFile();
			break;
		}

		$strategy::delete_cache();

		echo __CLASS__ . ": success<br>";
		return true;
	}
}
?>
