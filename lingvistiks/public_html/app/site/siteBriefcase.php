<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteBriefcase {
	static $allow_group = [3, 4];

	static function briefcase_list() {
		// title
		$page_info->page_title = ($_SESSION['user_group'] == 3) ? twig::$lang['briefcase_list_perfomens'] : twig::$lang['briefcase_list_owner'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id'] || !in_array($_SESSION['user_group'], self::$allow_group)) {
			site::error404();
		} else {

			// справочник по языкам
			$lang_list = get_book_for_essence(1);
			twig::assign('lang_list', $lang_list);
			// справочник по языкам

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			// pager
			$limit = 5;
			$start = get_current_page() * $limit - $limit;
			// pager

			$briefcase_type = (int)$_REQUEST['briefcase_type'] ? (int)$_REQUEST['briefcase_type'] : 1;

			if($_SESSION['user_group'] == 3) {
				switch ($briefcase_type) {
					case '1':
						$sql_join = "JOIN " . DB::$db_prefix . "_users_resume as rsm on rsm.resume_owner = rsp.response_resume";
						$sql_where = " AND response_type = '".(int)$briefcase_type."'";
					break;

					case '2':
						$sql_join = "JOIN " . DB::$db_prefix . "_users_order as ord on ord.order_id = rsp.response_work";
						$sql_where = " AND response_type = '".(int)$briefcase_type."'";
					break;
				}
			}

			if($_SESSION['user_group'] == 4) {
				switch ($briefcase_type) {
					case '1':
						$sql_join = "JOIN " . DB::$db_prefix . "_users_jobs as jsb on jsb.jobs_id = rsp.response_jobs";
						$sql_where = " AND response_type = '".(int)$briefcase_type."'";
					break;

					case '2':
						$sql_join = "JOIN " . DB::$db_prefix . "_users_order as ord on ord.order_id = rsp.response_work";
						$sql_where = " AND response_type = '".(int)$briefcase_type."'";
					break;
				}
			}


			if($briefcase_type) {
				$page_link = "&briefcase_type=".(int)$briefcase_type;
			}


			/*switch ($_SESSION['user_group']) {
				case '3':
					$sql_sv = " rsp.respons_owner ";
				break;
				case '4':
					$sql_sv = " rsp.response_perfomens ";
				break;
			}*/

			$respons_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response as rsp
				$sql_join
				JOIN " . DB::$db_prefix . "_users as usr on usr.id = rsp.response_from
				WHERE rsp.response_to = '". $profile_id ."' AND user_status = '1' $sql_where
				ORDER BY response_id DESC
				LIMIT " . $start . "," . $limit . "
			");

			foreach ($respons_list as $row) {

				if($row->user_group == 3) {

					// языковые пары
					$lang_var = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_langvar
						WHERE var_owner = '". $row->id ."'
					");

					foreach ($lang_var as $row2) {
						$row2->var_lang_from = $lang_list[$row2->var_lang_from];
						$row2->var_lang_to = $lang_list[$row2->var_lang_to];
					}
					$row->lang_var = $lang_var;
					// языковые пары

					$city_list = city_by_lang($row->user_country);
					$row->city_list = $city_list;

					$row = profile::profile_info($row);

					$sql2 = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_skill
						WHERE skill_owner = '". $row->id ."'
					");
					$row->skill_list = [];
					foreach ($sql2 as $value) {
						$row->skill_list[$value->skill_type] = $value;
					}
				}


				if($row->user_group == 4) {
					$city_list = city_by_lang($row->user_country);
					$row->city_list = $city_list;

					$row = profile::profile_info($row);
				}

				if($row->response_work) {
					$row = siteOrder::order_info($row);
				}

				if($row->response_jobs) {
					//$row = siteOrder::order_info($row);
				}

			}

		//	dd($respons_list);

			// pager
			$num = DB::numrows("
				SELECT rsp.response_id
				FROM " . DB::$db_prefix . "_users_response as rsp
				$sql_join
				JOIN " . DB::$db_prefix . "_users as usr on usr.id = rsp.response_from
				WHERE rsp.response_to = '". $profile_id ."' AND user_status = '1' $sql_where
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/briefcase/?filter=1". $page_link.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			twig::assign('respons_list', $respons_list);
			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/briefcase_list.tpl'));
		}
	}

}

?>
