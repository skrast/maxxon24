<?php
class cacheRedis extends cache {
	private static $redis;

	static function execute()
    {
		require_once(BASE_DIR . '/vendor/predis/vendor/autoload.php');
		self::$redis = new Predis\Client(config::app('app_redis')['cache'], array('prefix' => config::app('app_key_access').'_cache:'));
    }

	static function write($key, $result, $TTL = null) {

		// принудительное удаление кеша
		if($TTL == "-1") {
			self::delete($key);
			return false;
		}
		// принудительное удаление кеша

		if($TTL >= 1) {
			if(self::$redis->exists($key))
			{
				return false;
			}
			else
			{
				self::$redis->set($key, serialize($result));
				self::$redis->expire($key, $TTL);
				return true;
			}
		}
		return false;
	}

	static function check($key, $TTL = null) {
		if($TTL) {

			// принудительное удаление кеша
			if($TTL == "-1") {
				self::delete($key);
				return false;
			}
			// принудительное удаление кеша

			if(self::$redis->exists($key))
			{
				return unserialize(self::$redis->get($key));
			}
			else
			{
				return false;
			}
		} else {
			return false;
		}
	}

	static function delete($key) {
		if(self::$redis->exists($key))
		{
			self::$redis->set($key, "");
			self::$redis->expire($key, 0);
			return true;
		}
		return false;
	}

	static function delete_cache() {
		self::$redis->executeRaw(['FLUSHALL']);
	}
}
?>
