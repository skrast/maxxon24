<?php
class removeCalendar {
	static $desc = "Очистка календарей пользователей";
	static $period = 3600;
	static $group = 1;

	static function run_task() {

		set_time_limit(9999999);


		$graph = DB::fetchrow("
			DELETE FROM " . DB::$db_prefix . "_users_graph
			WHERE graph_end <= '".time()."'
		");
		
		echo __CLASS__ . ": success<br>";
		return true;
	}
}
?>
