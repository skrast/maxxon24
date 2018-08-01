<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteOtziv {

	static function get_otziv_by_id($profile_id, $limit, $start = 0) {
		$otziv_list = DB::fetchrow("
			SELECT
				*
			FROM " . DB::$db_prefix . "_users_otziv as otz
			JOIN " . DB::$db_prefix . "_users as usr on usr.id = otz.otziv_owner
			JOIN " . DB::$db_prefix . "_users_order as ord on ord.order_id = otz.otzive_order
			WHERE otziv_to_id = '". $profile_id ."'
			ORDER BY otziv_id DESC
			LIMIT " . $start . "," . $limit . "
		");
		foreach ($otziv_list as $row) {
			$row = self::otziv_info($row);
		}

		return $otziv_list;
	}

	static function otziv_info($otziv_info) {
		$user_work = get_work_user();
		$otziv_info->otziv_date = unix_to_date($otziv_info->otziv_date);
		$otziv_info->otziv_owner = $user_work[$otziv_info->otziv_owner];
		$otziv_info->otziv_to_id = $user_work[$otziv_info->otziv_to_id];

		$otziv_info = siteOrder::order_info($otziv_info);
		$otziv_info = profile::profile_info($otziv_info);

		$country_list = country_by_lang();
		$otziv_info->order_title = twig::$lang["lk_skill_array"][$otziv_info->order_skill] . ", " . $otziv_info->order_lang_from->title . " - " . $otziv_info->order_lang_to->title . ", " . $country_list[$otziv_info->order_country]['title'] . ", " . $otziv_info->order_city['title'];

		return $otziv_info;
	}
}
?>
