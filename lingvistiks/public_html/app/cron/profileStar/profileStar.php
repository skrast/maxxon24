<?php
class profileStar {
	static $desc = "Обновление рейтинга пользователей";
	static $period = 1;
	static $group = 1;

	static function run_task() {

		set_time_limit(9999999);

		$limit = 50;

		$cronfile = BASE_DIR . "/app/cron/profileStar/last.txt";
		create_file($cronfile, 0);
		$last_time = file_get_contents($cronfile);
		$last_time = (int)$last_time;

		$users = DB::fetchrow("
			SELECT
			*,
			(SELECT COUNT(otz.otziv_id) FROM " . DB::$db_prefix . "_users_otziv as otz WHERE otz.otziv_to_id = usr.id) as count_otziv,
			(SELECT SUM(otz.otziv_star) FROM " . DB::$db_prefix . "_users_otziv as otz WHERE otz.otziv_to_id = usr.id) as sum_otziv
			FROM " . DB::$db_prefix . "_users as usr
			WHERE user_status = '1' AND user_group IN (3,4) AND id >= '".$last_time."'
			ORDER BY user_visittime DESC
			LIMIT $limit
		");

		foreach ($users as $row) {
			DB::fetchrow("
				UPDATE
					" . DB::$db_prefix . "_users as usr
				SET
					user_rang = '".(format_string_number($row->sum_otziv/$row->count_otziv, true))."'
				WHERE id = '".$row->id."'
			");
		}

		$last_time = end($users);
		if(count($users)<$limit) {
			$last_time = 0;
		}
		file_put_contents($cronfile, $last_user->id);
		echo __CLASS__ . ": success<br>";
		return true;
	}
}
?>
