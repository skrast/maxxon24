<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteFriends {

	static function friends_add() {

		$my_id = $_SESSION['user_id'];
		$my_friend = (int)$_REQUEST['my_friend'];
		$my_friend_info = get_user_info($my_friend);

		if($my_id && $my_friend && $my_friend_info && $my_id!=$my_friend) {

			$is_friend = self::is_friends($my_id, $my_friend);

			if($is_friend) {
				DB::query("
					DELETE
					FROM " . DB::$db_prefix . "_users_friends
					WHERE friends_owner = '". $my_id ."' AND friends_to = '".$my_friend."'
				");

				$title = ($my_friend_info->user_group == 3) ? twig::$lang['friends_perfomens_delete'] : twig::$lang['friends_owner_delete'];
				echo json_encode(array("respons"=>$title, "is_friends"=>0, "status"=>"success"));
			} else {
				DB::query("
					INSERT INTO " . DB::$db_prefix . "_users_friends
					SET
					 	friends_owner = '". $my_id ."',
						friends_to = '".$my_friend."',
						friends_date = '".time()."'
				");

				$title = ($my_friend_info->user_group == 3) ? twig::$lang['friends_perfomens_add'] : twig::$lang['friends_owner_add'];
				echo json_encode(array("respons"=>$title, "is_friends"=>1, "status"=>"success"));
			}
		}

		exit;
	}

	static function is_friends($my_id, $my_friend) {

		$is_friend = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_friends
			WHERE friends_owner = '". $my_id ."' AND friends_to = '".$my_friend."'
		");

		return ($is_friend && $my_id!=$my_friend) ? true : false;
	}

	static function friends_list() {
		// title
		$page_info->page_title = twig::$lang['friends_list'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id']) {
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

			/*$user_skill = (int)$_REQUEST['user_skill'];
			$user_skill = ($user_skill && array_key_exists($user_skill, twig::$lang['lk_skill_array'])) ? " AND user_skill LIKE '%".(int)$user_skill."%'" : "";*/

			$user_group = (int)$_REQUEST['user_group'];
			$user_group = ($user_group && array_key_exists($user_group, twig::$lang['lk_group_array'])) ? " AND user_group = '".(int)$user_group."'" : "";

			// pager
			$limit = 5;
			$start = get_current_page() * $limit - $limit;
			// pager

			$friends_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_friends as fr
				JOIN " . DB::$db_prefix . "_users as usr on usr.id = fr.friends_to
				WHERE friends_owner = '". $profile_id ."' AND user_status = '1' $user_group
				ORDER BY friends_date DESC
				LIMIT " . $start . "," . $limit . "
			");

			foreach ($friends_list as $row) {
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

				if($row->user_type_form == 2 && $row->user_group == 4) {
					$row->company_info = DB::row("
						SELECT * FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$row->id."'
					");
				}

				$row->skill_list = DB::fetchrow("
					SELECT *
					FROM " . DB::$db_prefix . "_users_skill
					WHERE skill_owner = '". $row->id ."'
				");

				$row->user_company = DB::row("
					SELECT *
					FROM " . DB::$db_prefix . "_users_company
					WHERE company_owner = '". $row->id ."'
				");

				$row = profile::profile_info($row);
			}


			// pager
			$num = DB::numrows("
				SELECT
					usr.id
				FROM " . DB::$db_prefix . "_users_friends as fr
				JOIN " . DB::$db_prefix . "_users as usr on usr.id = fr.friends_to
				WHERE friends_owner = '". $profile_id ."' AND user_status = '1' $user_skill $user_group
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/customers-partners/?filter=1". $page_link.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			twig::assign('friends_list', $friends_list);
			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/friends_list.tpl'));
		}
	}

}

?>
