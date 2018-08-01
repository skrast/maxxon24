<?php
class siteHelper {

	// пользователи на главной
	function get_user_index () {
		//site::url_parse_helper();
		$users_allow = config::app('site_index_user');

		$users = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users
			WHERE user_group = '3' AND user_lang = '".site::$lang."' AND user_status = '1' AND user_skill != '' AND user_photo != ''
			ORDER BY RAND()
			LIMIT 15
		");

		foreach ($users as $row) {
			$row->user_skill = explode(",",$row->user_skill);
			$row = profile::profile_info($row);
		}

		twig::assign('user_list', $users);
	}

	// новости на главной
	function get_news_index () {

		$news = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1'
			ORDER BY page_id DESC
			LIMIT 4
		");

		foreach ($news as $row) {
			$row = modules\page::page_info($row);
		}

		twig::assign('news_list', $news);
	}

	// новости в раздел
	function get_news_list () {
		$limit = 5;

		// pager
		$start = get_current_page() * $limit - $limit;
		// pager

		$sql_tags = "";
		$sql_page = "";
		if($_REQUEST['tags_title']) {
			$tags_title = $_REQUEST['tags_title'];
			$tags_temp = [];
			foreach ($tags_title as $key => $value) {
				if(mb_strlen(trim($value)) >= \config::app('app_min_strlen')) {
					$tags_temp[] = clear_text($value);
				}
			}
			$page_tags = array_unique($tags_temp);

			if($page_tags) {
				foreach ($page_tags as $key => $value) {
					$sql_page .= "&tags_title[]=".urlencode(clear_text($value));
				}
				//$sql_page .= "&" . implode("&", $sql_page);
				$page_tags = implode("%' OR tags_title LIKE '%", $page_tags);
				$sql_tags = " AND page_id IN (SELECT page_id FROM " . DB::$db_prefix . "_module_page_tags WHERE tags_title LIKE '%".$page_tags."%')";
			}
		}

		$news = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1' $sql_tags
			ORDER BY page_id DESC
			LIMIT " . $start . "," . $limit . "
		");

		foreach ($news as $row) {
			$row = modules\page::page_info($row);
		}

		$url_info = site::url_parse_helper();
		$page_info = site::url_parse($url_info);

		// pager
		$num = \DB::numrows("
			SELECT
				page_id
			FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1' $sql_tags
		");
		\twig::assign('num', $num);
		if ($num > $limit)
		{
			$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/".$page_info->page_alias.'?search=1&page={s}'.$sql_page.'">{t}</a>');
			\twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('news_list', $news);

		// теги
		$tags_view = DB::fetchrow("
			SELECT
				COUNT(page_id) as count_tags, tags_title
			FROM
				" . DB::$db_prefix . "_module_page_tags
			WHERE tags_type = '2'
			GROUP BY tags_title
			ORDER BY count_tags DESC, tags_title ASC
			LIMIT 5
		");
		twig::assign('tags_view', $tags_view);
		// теги
	}


	// публикации в раздел
	function get_doc_list () {
		$limit = 5;

		// pager
		$start = get_current_page() * $limit - $limit;
		// pager

		$sql_tags = "";
		$sql_page = [];
		if($_REQUEST['tags_title']) {
			$tags_title = $_REQUEST['tags_title'];
			$tags_temp = [];
			foreach ($tags_title as $key => $value) {
				if(mb_strlen(trim($value)) >= \config::app('app_min_strlen')) {
					$tags_temp[] = clear_text($value);
				}
			}
			$page_tags = array_unique($tags_temp);

			if($page_tags) {
				foreach ($page_tags as $key => $value) {
					$sql_page[] = "tags_title[]=".urlencode(clear_text($value));
				}
				$sql_page = "&" . implode("&", $sql_page);
				$page_tags = implode("%' OR tags_title LIKE '%", $page_tags);
				$sql_tags = " AND page_id IN (SELECT page_id FROM " . DB::$db_prefix . "_module_page_tags WHERE tags_title LIKE '%".$page_tags."%')";
			}
		}

		$docs = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '6' AND page_lang = '".site::$lang."' AND page_status = '1' $sql_tags
			ORDER BY page_id DESC
			LIMIT " . $start . "," . $limit . "
		");

		foreach ($docs as $row) {
			$row = modules\page::page_info($row);
		}

		$url_info = site::url_parse_helper();
		$page_info = site::url_parse($url_info);

		// pager
		$num = \DB::numrows("
			SELECT
				page_id
			FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '6' AND page_lang = '".site::$lang."' AND page_status = '1' $sql_tags
		");
		\twig::assign('num', $num);
		if ($num > $limit)
		{
			$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/".$page_info->page_alias.'?search=1&page={s}'.$sql_page.'">{t}</a>');
			\twig::assign('page_nav', $page_nav);
		}
		// pager

		twig::assign('doc_list', $docs);

		// теги
		$tags_view = DB::fetchrow("
			SELECT
				COUNT(page_id) as count_tags, tags_title
			FROM
				" . DB::$db_prefix . "_module_page_tags
			WHERE tags_type = '6'
			GROUP BY tags_title
			ORDER BY count_tags DESC, tags_title ASC
			LIMIT 5
		");
		twig::assign('tags_view', $tags_view);
		// теги

		$news_view = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1'
			ORDER BY page_view DESC
			LIMIT 3
		");

		foreach ($news_view as $row) {
			$row = modules\page::page_info($row);
		}

		twig::assign('news_view_list', $news_view);
	}

	// список всех партнеров на страницах сайта
	static function get_partner_page() {

		$partners = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '3' AND page_lang = '".site::$lang."' AND page_status = '1'
			ORDER BY page_id DESC
		");

		foreach ($partners as $row) {
			$row = modules\page::page_info($row);
		}

		twig::assign('partner_list', $partners);
	}

	static function get_photos_list($page_id) {
		$module_info = modules\page::module_info();
		\twig::assign('module_info', $module_info);

		$photos = modules\page::get_page_photo($page_id);
		twig::assign('photos', $photos);
	}

	static function load_book_by_id() {
		$book_id = (int)$_REQUEST['book_id'];
		$book_list = get_book_for_essence($book_id);

		echo json_encode(array("respons"=>$book_list, "status"=>"success"));
		exit;
	}

	static function get_filter_index() {
		$service_filter = [];
		foreach (siteOrder::$book_link as $key => $value) {
			$service_filter[$key] = get_book_for_essence($value);
		}
		twig::assign('service_filter', $service_filter);
	}


	static function tarif_page() {
		twig::assign('no_pay_link', 1);

		$tarif_list = siteBilling::get_tarif_group(3, 1);
		twig::assign('tarif_list', $tarif_list);
		twig::assign('user_group', 3);
		twig::assign('user_subgroup', 1);
		$html = twig::fetch('frontend/chank/tariff_list.tpl');
		twig::assign('tariff_block_1', $html);

		$tarif_list = siteBilling::get_tarif_group(3, 2);
		twig::assign('tarif_list', $tarif_list);
		twig::assign('user_group', 3);
		twig::assign('user_subgroup', 2);
		$html = twig::fetch('frontend/chank/tariff_list.tpl');
		twig::assign('tariff_block_2', $html);

		$tarif_list = siteBilling::get_tarif_group(4, 1);
		twig::assign('tarif_list', $tarif_list);
		twig::assign('user_group', 4);
		twig::assign('user_subgroup', 1);
		$html = twig::fetch('frontend/chank/tariff_list.tpl');
		twig::assign('tariff_block_3', $html);

		$tarif_list = siteBilling::get_tarif_group(4, 2);
		twig::assign('tarif_list', $tarif_list);
		twig::assign('user_group', 4);
		twig::assign('user_subgroup', 2);
		$html = twig::fetch('frontend/chank/tariff_list.tpl');
		twig::assign('tariff_block_4', $html);

	}
}
?>
