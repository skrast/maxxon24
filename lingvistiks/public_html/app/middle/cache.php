<?php
abstract class cache {
	private static $strategy;

	static function connect() {
		switch (config::app('app_type_cache')) {
			case 'redis':
				self::$strategy = new cacheRedis();
			break;

			case 'file':
			default:
				self::$strategy = new cacheFile();
			break;
		}

		self::$strategy::execute();
	}

	public static function check($query, $TTL = null) {
		return self::$strategy::check($query, $TTL);
	}

	public static function write($query, $result, $TTL = null) {
		return self::$strategy::write($query, $result, $TTL);
	}

	public static function delete($query) {
		return self::$strategy::delete($query);
	}

	static function delete_cache() {
		self::$strategy::delete_cache();
	}
}
?>
