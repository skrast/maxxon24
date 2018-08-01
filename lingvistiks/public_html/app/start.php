<?php

class start {
	static $start;
	static $end;
	static $user_id;

	static $task_responsible_id;
	static $deal_owner_id;
	static $company_owner_id;

	static $main_title =  array();

	function __construct() {

		// дефолтные значения
		self::$start = time()-2592000;
		self::$end = time();

		self::$main_title[] =  twig::$lang["start_name"];
	}

	static function bild_page() {

		twig::assign('hook_start_bild', hook::show_hook('hook_start_bild'));
		twig::assign('content', twig::fetch('start.tpl'));
    }

    /* Проверка соответствия тарифному плану */
    static function check_curent_load() {
        $curent_info_load = array();

        $curent_info_load['disk'] = self::dir_size(BASE_DIR . '/' . config::app('app_upload_dir') . '/');
        $curent_info_load['disk_use'] = getSymbolByQuantity($curent_info_load['disk']);
        $curent_info_load['disk_allow'] = getSymbolByQuantity(config::app('app_bandwidth_max'));
        $curent_info_load['disk_use_persent'] = ($curent_info_load['disk']*100)/config::app('app_bandwidth_max');
        $curent_info_load['disk_allow_persent'] = 100-$curent_info_load['disk_use_persent'];

        $curent_info_load['db'] = DB::single("
			SELECT SUM( data_length + index_length ) AS 'size'
			FROM information_schema.TABLES
			WHERE table_schema = '" . DB::$db_name . "' AND table_name LIKE '" . DB::$db_prefix . "%'
			LIMIT 1
		");
        $curent_info_load['db_use'] = getSymbolByQuantity($curent_info_load['db']);
        $curent_info_load['db_allow'] = getSymbolByQuantity(config::app('app_db_max'));
        $curent_info_load['db_use_persent'] = ($curent_info_load['db']*100)/config::app('app_db_max');
        $curent_info_load['db_allow_persent'] = 100-$curent_info_load['db_use_persent'];

        return $curent_info_load;
    }

    static function dir_size($dirname) {
        $totalsize=0;
        if ($dirstream = @opendir($dirname)) {
            while (false !== ($filename = readdir($dirstream))) {
                if ($filename!="." && $filename!="..")
                {
                    if (is_file($dirname."/".$filename))
                        $totalsize+=filesize($dirname."/".$filename);

                    if (is_dir($dirname."/".$filename))
                        $totalsize+=self::dir_size($dirname."/".$filename);
                }
            }
        }
        closedir($dirstream);
        return $totalsize;
    }
    /* Проверка соответствия тарифному плану */

	static function get_cloud_stat() {
		if(!isset($_SESSION['alles'])) return false;

		$name_cache = "cloud_cache";
		$TTL = 1800;

		// Проверка соответствия тарифному плану
		$result = cache::check(md5($name_cache), $TTL);
		if($result === false) {
			$result = self::check_curent_load();
			cache::write(md5($name_cache), $result, $TTL);
		}

		twig::assign('curent_info_load', $result);
		$html = twig::fetch('chank/cloud_stat.tpl');
		echo json_encode(array("status"=>"success", "html"=>$html));
		exit;
		// Проверка соответствия тарифному плану
	}
}

?>
