<?php
class cacheFile extends cache  {
	static function execute()
    {

    }

	static function write($cache_file, $result, $TTL = null) {
		$cache_dir = BASE_DIR . '/'.config::app('app_storage_dir').'/' . substr($cache_file, 0, 2) . '/' . substr($cache_file, 2, 2) . '/' . substr($cache_file, 4, 2) . '/';

		// принудительное удаление кеша
		if($TTL == "-1") {
			self::delete($cache_file);
			return false;
		}
		// принудительное удаление кеша

		if($TTL >= 1) {
			if(! file_exists($cache_dir)) mkdir($cache_dir, 0777, true);

			if(! (file_exists($cache_dir . $cache_file) && ($TTL == -1 ? true : @time() - @filemtime($cache_dir . $cache_file) < $TTL)))
			{
				file_put_contents($cache_dir . $cache_file, serialize($result));
				return true;
			}
		}
        return false;
	}

	static function check($cache_file, $TTL = null) {
		if($TTL) {
			$cache_dir = BASE_DIR . '/'.config::app('app_storage_dir').'/' . substr($cache_file, 0, 2) . '/' . substr($cache_file, 2, 2) . '/' . substr($cache_file, 4, 2) . '/';

			// принудительное удаление кеша
			if($TTL == "-1") {
				self::delete($cache_file);
				return false;
			}
			// принудительное удаление кеша

			if(! file_exists($cache_dir)) mkdir($cache_dir, 0777, true);
			if(! (file_exists($cache_dir . $cache_file) && ($TTL == -1 ? true : @time() - @filemtime($cache_dir . $cache_file) < $TTL)))
			{
				return false;
			}
			else
			{
				return unserialize(file_get_contents($cache_dir . $cache_file));
			}
		} else {
			return false;
		}
	}

	static function delete($cache_file) {
		$cache_dir = BASE_DIR . '/'.config::app('app_storage_dir').'/' . substr($cache_file, 0, 2) . '/' . substr($cache_file, 2, 2) . '/' . substr($cache_file, 4, 2) . '/';
		if(file_exists($cache_dir.$cache_file)) {
			unlink($cache_dir.$cache_file);
			return true;
		}
		return false;
	}

	static function delete_cache() {
		rrmdir(BASE_DIR . "/" . config::app('app_storage_dir'));
	}
}
?>
