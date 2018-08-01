<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteSearch {

	static $metro = [
		1 => 18,
	];

	static function bild_perfomer_search_filter() {
		// поиск
		$page_link = "";
		$sql_link = "";

		if(!empty($_REQUEST['user_country'])) {
			$sql_link .= " AND user_country = '".(int)$_REQUEST['user_country']."' ";
			$page_link .= "&user_country=".(int)$_REQUEST['user_country'];
		}

		if(!empty($_REQUEST['user_city'])) {
			$sql_link .= " AND user_city = '".(int)$_REQUEST['user_city']."' ";
			$page_link .= "&user_city=".(int)$_REQUEST['user_city'];
		}

		if(!empty($_REQUEST['user_type']) && $_REQUEST['user_type'] >= 1) {
			$user_type = [1,2,3,4,5,6];
			switch ($_REQUEST['user_type']) {
				case '1':
					$user_type = [1, 4];
				break;
				case '2':
					$user_type = [2, 5];
				break;
				case '3':
					$user_type = [3, 6];
				break;
			}

			$sql_link .= " AND (
				SELECT COUNT(comn.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as comn WHERE comn.serv_owner = usr.id AND serv_service IN (".implode(",", $user_type).")
			) > 0  ";
			$page_link .= "&user_type=".(int)$_REQUEST['user_type'];
		}

		if(!empty($_REQUEST['serv_type_service'])) {
			/*foreach ($_REQUEST['serv_type_service'] as $value) {
				$ssse[] = (int)$value;
				$page_link .= "&serv_type_service[]=".(int)$value;
			}*/

			$sql_link .= " AND (
				SELECT COUNT(srv.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as srv WHERE srv.serv_owner = usr.id AND serv_type_service = '".(int)$_REQUEST['serv_type_service']."'
			) > 0  ";
			$page_link .= "&serv_type_service=".(int)$_REQUEST['serv_type_service'];
		}

		if(!empty($_REQUEST['serv_communication'])) {
			foreach ($_REQUEST['serv_communication'] as $value) {
				$ssse[] = (int)$value;
				$page_link .= "&serv_communication[]=".(int)$value;
			}

			$sql_link .= " AND (
				SELECT COUNT(comn.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as comn WHERE comn.serv_owner = usr.id AND serv_communication IN (".implode(",",$ssse).")
			) > 0  ";
		}

		if(!empty($_REQUEST['serv_theme'])) {
			/*foreach ($_REQUEST['serv_theme'] as $value) {
				$ssse[] = (int)$value;
				
			}*/

			$sql_link .= " AND (
				SELECT COUNT(comn.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as comn WHERE comn.serv_owner = usr.id AND serv_theme = '".(int)$_REQUEST['serv_theme']."'
			) > 0  ";
			$page_link .= "&serv_theme=".(int)$_REQUEST['serv_theme'];
		}

		if(!empty($_REQUEST['serv_level'])) {
			$ssse = (int)$_REQUEST['serv_level'];
			$page_link .= "&serv_level=".(int)$_REQUEST['serv_level'];

			$sql_link .= " AND (
				SELECT COUNT(comn.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as comn WHERE comn.serv_owner = usr.id AND serv_level = (".$ssse.")
			) > 0  ";
		}

		if(!empty($_REQUEST['serv_place'])) {
			$sql_like = [];
			foreach ($_REQUEST['serv_place'] as $value) {
				$page_link .= "&serv_place[]=".(int)$value;
				$sql_like[] = " user_place_work LIKE '%".(int)$value."%'";

			}
			$sql_like = implode(" OR ", $sql_like);
			$sql_link .= " AND ($sql_like) ";
		}

		if(!empty($_REQUEST['lang_from_temp']) && !empty($_REQUEST['lang_to_temp']) && $_REQUEST['lang_from_temp']!=$_REQUEST['lang_to_temp']) {
			$sql_link .= " AND (
				SELECT COUNT(lng.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as lng WHERE lng.serv_owner = usr.id AND ((serv_lang_from = '".(int)$_REQUEST['lang_from_temp']."' AND serv_lang_to = '".(int)$_REQUEST['lang_to_temp']."') OR (serv_lang_from = '".(int)$_REQUEST['lang_to_temp']."' AND serv_lang_to = '".(int)$_REQUEST['lang_from_temp']."'))
			) > 0  ";

			$page_link .= "&lang_from_temp=".(int)$_REQUEST['lang_from_temp']."&lang_to_temp=".(int)$_REQUEST['lang_to_temp'];
		}

		if(!empty(date_to_unix($_REQUEST['graph_start'])) && !empty(date_to_unix($_REQUEST['graph_end'])) && date_to_unix($_REQUEST['graph_start'])<date_to_unix($_REQUEST['graph_end'])) {
			$sql_link .= " AND (
				SELECT COUNT(lng.graph_id) as ctt FROM " . DB::$db_prefix . "_users_graph as lng WHERE lng.graph_owner = usr.id AND graph_start >= '".date_to_unix($_REQUEST['graph_start'])."' AND graph_end <= '".date_to_unix($_REQUEST['graph_end'])."'
			) > 0  ";

			$page_link .= "&graph_start=".urlencode(clear_text($_REQUEST['lang_from_temp']))."&graph_end=".urlencode(clear_text($_REQUEST['lang_to_temp']));
		}

		if(!empty($_REQUEST['budget_start']) && !empty($_REQUEST['budget_end'])) {
			foreach ($_REQUEST['budget_start'] as $key => $value) {
				if($_REQUEST['budget_start'][$key] > 0 && $_REQUEST['budget_end'][$key] > 0) {

					$sql_link .= " AND (
						SELECT COUNT(bgt.serv_id) as ctt FROM " . DB::$db_prefix . "_users_services as bgt WHERE bgt.serv_owner = usr.id AND serv_coast BETWEEN '".format_string_number($_REQUEST['budget_start'][$key])."' AND '".format_string_number($_REQUEST['budget_end'][$key])."' AND serv_currency = '".(int)$_REQUEST['budget_currency'][$key]."' AND serv_time = '".(int)$key."'
					) > 0  ";

					$page_link .=
					 "&budget_start[".$key."]=".format_string_number($_REQUEST['budget_start'][$key]).
					 "&budget_end[".$key."]=".format_string_number($_REQUEST['budget_end'][$key]).
					 "&budget_currency[".$key."]=".(int)$_REQUEST['budget_currency'][$key].
					 "&serv_time[".$key."]=".(int)$key;
			 	}
			}
		}

		return array($page_link, $sql_link);
	}

	static function perfomer_search() {

		// сборка параметров для поиска
		$bild_filter = self::bild_perfomer_search_filter();
		$page_link = $bild_filter[0];
		$sql_link = $bild_filter[1];
		// сборка параметров для поиска

		// title
		$page_info->page_title = twig::$lang['search_title'];
		twig::assign('page_info', $page_info);
		// title

		$default = ($_REQUEST['user_type']) ? siteOrder::$book_link[$_REQUEST['user_type']] : siteOrder::$default_book_link;
		$service_list = get_book_for_essence($default);
		twig::assign('service_list', $service_list);
		twig::assign('book_link', siteOrder::$book_link);

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		$city_list = city_by_lang($_REQUEST['user_country']);
		twig::assign('city_list', $city_list);

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		// справочник по средствам связи
		$communication_list = get_book_for_essence(4);
		twig::assign('communication_list', $communication_list);
		// справочник по средствам связи

		// справочник по опыту работы
		$time_list = get_book_for_essence(8);
		twig::assign('time_list', $time_list);
		// справочник по опыту работы

		// справочник по опыту работы
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		// справочник по опыту работы

		$place_list = get_book_for_essence(17);
		twig::assign('place_list', $place_list);

		$theme_list = get_book_for_essence(2);
		twig::assign('theme_list', $theme_list);

		$level_list = get_book_for_essence(14);
		twig::assign('level_list', $level_list);

		$place_work_list = get_book_for_essence(17);
		twig::assign('place_work_list', $place_work_list);

		$limit = 10;
		// pager
		$start = get_current_page() * $limit - $limit;
		// pager

		$sql = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users as usr
			WHERE user_status = '1' AND user_group = '3'  $sql_link
			ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
			LIMIT " . $start . "," . $limit . "
		");

		$perfomer_list = [];
		$perfomer_list_var = [];
		foreach ($sql as $row) {

			$row->user_default_lang = $lang_list[$row->user_default_lang];

			$row->city_list = city_by_lang($row->user_country);

			$perfomer_list[$row->id] = profile::profile_info($row);
			$perfomer_list_var[] = $row->id;
		}

		if($perfomer_list_var) {
			// языковые пары
			$lang_var = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_services
				WHERE serv_owner IN (".implode(",", $perfomer_list_var).")
			");
			foreach ($lang_var as $row) {
				$row->serv_lang_from = $lang_list[$row->serv_lang_from];
				$row->serv_lang_to = $lang_list[$row->serv_lang_to];

				$perfomer_list[$row->serv_owner]->lang_var[$row->serv_lang_from->id."_".$row->serv_lang_to->id] = $row;
			}
			// языковые пары
			twig::assign('perfomer_list', $perfomer_list);
		}

		$url = site::url_parse_helper();
		$url['page_url'] = explode("?", $url['page_url'])[0];

		switch ($_REQUEST['user_type']) {
			case '2':
				$type_search = "prepodavatel";
			break;
			case '3':
				$type_search = "gid";
			break;
			default:
				$type_search = "perevodchik";
			break;
		}
		\twig::assign('type_search', $type_search);

		// pager
		$num = DB::numrows("
			SELECT * FROM
				" . DB::$db_prefix . "_users  as usr
			WHERE user_status = '1' AND user_group = '3'  $sql_link
		");
		twig::assign('num', $num);
		if ($num > $limit)
		{
			$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' . HOST_NAME . site::$link_lang_pref . "/".$type_search."/?filter=1". $page_link.'&page={s}">{t}</a>');
			\twig::assign('page_nav', $page_nav);
		}
		// pager

		$html = twig::fetch('frontend/chank/perfomens_col.tpl');
		twig::assign('perfomens_col', $html);

		$form_filter = '';
		switch ($_REQUEST['user_type']) {
			case '1':
				$form_filter = $_SESSION['user_id'] ? "perevodchik_filter_user" : "perevodchik_filter_guest";
			break;
			case '2':
				$form_filter = $_SESSION['user_id'] ? "prepodavatel_filter_user" : "prepodavatel_filter_guest";
			break;
			case '3':
				$form_filter = $_SESSION['user_id'] ? "gid_filter_user" : "gid_filter_guest";
			break;
		}
		$form_filter = twig::fetch('frontend/chank/'.$form_filter.'.tpl');
		twig::assign('form_filter', $form_filter);

		twig::assign('content', twig::fetch('frontend/perfomer_search.tpl'));
	}

	static function modal_search() {
		$void_id = (int)$_REQUEST['void_id'];

		$title = twig::$lang['search_title'];

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		$service_list = [];
		foreach (siteOrder::$book_link as $key => $value) {
			$service_list[$key] = get_book_for_essence($value);
		}
		twig::assign('service_list', $service_list);
		twig::assign('void_id', $void_id);

		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);

		$theme_list = get_book_for_essence(2);
		twig::assign('theme_list', $theme_list);

		$level_list = get_book_for_essence(14);
		twig::assign('level_list', $level_list);

		$communication_list = get_book_for_essence(4);
		twig::assign('communication_list', $communication_list);

		$time_list = get_book_for_essence(8);
		twig::assign('time_list', $time_list);

		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);

		$place_list = get_book_for_essence(17);
		twig::assign('place_list', $place_list);


		$html = twig::fetch('frontend/chank/modal_search.tpl');
		echo json_encode(array("title"=>$title, "html"=>$html, "status"=>"success"));
		exit;
	}

	static function search_writer() {
		$title = twig::$lang['search_writer_form'];

		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);

		$theme_list = get_book_for_essence(2);
		twig::assign('theme_list', $theme_list);

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		if($_REQUEST['save']) {

			$error = [];

			if($_REQUEST['document_hidden'] == 1 && $_REQUEST['document_privacy'] != 1) {
				$error[] = twig::$lang['search_writer_hidden_document_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if($_REQUEST['document_from_lang'] == $_REQUEST['document_to_lang']) {
				$error[] = twig::$lang['search_writer_document_from_to_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			$document_date = date_to_unix($_REQUEST['document_date']);
			if($document_date < time()) {
				$error[] = twig::$lang['search_writer_document_date_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(($_REQUEST['has_file']-1)<1 && !$_REQUEST['document_file_link']) {
				$error[] = twig::$lang['search_writer_document_file_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			$total_size = 0;
			foreach ($_FILES['document_file']['name'] as $key => $value) {
				if (!empty($_FILES['document_file']['name'][$key])) {
					$total_size = $total_size+$_FILES['document_file']['size'][$key];
				}
			}

			if($total_size>=(10 * 1024 * 1024)) {
				$error[] = twig::$lang['search_writer_document_file_size_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			switch ($_REQUEST['document_way']) {
				case '1':
					$_REQUEST['document_country_from'] = "";
					$_REQUEST['document_city_from'] = "";
					$_REQUEST['document_metro_from'] = "";
					$_REQUEST['document_address_from'] = "";

					$_REQUEST['document_country_to'] = 0;
					$_REQUEST['document_city_to'] = 0;
					$_REQUEST['document_metro_to'] = 0;
					$_REQUEST['document_address_to'] = "";

					$_REQUEST['document_country_offline'] = 0;
					$_REQUEST['document_city_offline'] = 0;
				break;

				case '2':

					$_REQUEST['document_country_to'] = 0;
					$_REQUEST['document_city_to'] = 0;
					$_REQUEST['document_metro_to'] = 0;
					$_REQUEST['document_address_to'] = "";

					$_REQUEST['document_country_offline'] = 0;
					$_REQUEST['document_city_offline'] = 0;

					if(!$_REQUEST['document_country_from'] || !$_REQUEST['document_city_from']) {
						$error[] = twig::$lang['search_writer_document_country_city_error'];
						twig::assign('error', $error);
						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}

					if(!$_REQUEST['document_address_from']) {
						$error[] = twig::$lang['search_writer_document_address_error'];
						twig::assign('error', $error);
						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}


				break;

				case '3':
					$_REQUEST['document_country_from'] = "";
					$_REQUEST['document_city_from'] = "";
					$_REQUEST['document_metro_from'] = "";
					$_REQUEST['document_address_from'] = "";

					$_REQUEST['document_country_offline'] = 0;
					$_REQUEST['document_city_offline'] = 0;

					if(!$_REQUEST['document_country_to'] || !$_REQUEST['document_city_to']) {
						$error[] = twig::$lang['search_writer_document_country_city_error'];
						twig::assign('error', $error);
						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}

					if(!$_REQUEST['document_address_to']) {
						$error[] = twig::$lang['search_writer_document_address_error'];
						twig::assign('error', $error);
						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}
				break;

				case '4':
					$_REQUEST['document_country_from'] = "";
					$_REQUEST['document_city_from'] = "";
					$_REQUEST['document_metro_from'] = "";
					$_REQUEST['document_address_from'] = "";

					$_REQUEST['document_country_to'] = 0;
					$_REQUEST['document_city_to'] = 0;
					$_REQUEST['document_metro_to'] = 0;
					$_REQUEST['document_address_to'] = "";

					if(!$_REQUEST['document_country_offline'] || !$_REQUEST['document_city_offline']) {
						$error[] = twig::$lang['search_writer_document_country_city_error'];
						twig::assign('error', $error);
						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}
				break;
			}

			if($error) {
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(backend::isAjax()) {
				echo json_encode(array("upload"=>true, "status"=>"success"));
				exit;
			}

			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_writer
				SET
					document_file_link = '".clear_text($_REQUEST['document_file_link'])."',
					document_hidden = '". ($_REQUEST['document_hidden'] ? 1 : 0)."',
					document_privacy = '". ($_REQUEST['document_privacy'] ? 1 : 0)."',
					document_from_lang = '". (int)$_REQUEST['document_from_lang'] ."',
					document_to_lang = '". (int)$_REQUEST['document_to_lang'] ."',
					document_theme = '". (int)$_REQUEST['document_theme'] ."',
					document_date = '". date_to_unix($_REQUEST['document_date']) ."',
					document_verif = '". (int)$_REQUEST['document_verif']."',
					document_way = '". (int)$_REQUEST['document_way']."',
					document_country_from = '". (int)$_REQUEST['document_country_from']."',
					document_city_from = '". (int)$_REQUEST['document_city_from']."',
					document_metro_from = '". (int)$_REQUEST['document_metro_from']."',
					document_address_from = '". clear_text($_REQUEST['document_address_from'])."',
					document_country_to = '". (int)$_REQUEST['document_country_to']."',
					document_city_to = '". (int)$_REQUEST['document_city_to']."',
					document_metro_to = '". (int)$_REQUEST['document_metro_to']."',
					document_address_to = '". clear_text($_REQUEST['document_address_to'])."',
					document_country_offline = '". (int)$_REQUEST['document_country_offline']."',
					document_city_offline = '". (int)$_REQUEST['document_city_offline']."',
					document_comment = '". clear_text($_REQUEST['document_comment'])."',
					document_owner = '".$_SESSION['user_id']."',
					document_add = '". time() ."'
			");
			$document_id = DB::lastInsertId();

			if(!$_SESSION['user_group']) {
				$_SESSION['user_writer'][] = $document_id;
			} else {
				$moder = DB::row("SELECT * FROM " . DB::$db_prefix . "_users_writer WHERE user_group = '2' AND user_status = '1' ORDER BY user_visittime DESC LIMIT 1");

				// сообщения о заказе
				$message = twig::$lang['message_user_writer_add'] . " ID №".$document_id;
				siteMessage::message_add($_SESSION['user_id'], $moder->id, $message, $document_id);
				// сообщения о заказе
			}

			// загрузка файлов
			$total_size = 0;
			foreach ($_FILES['document_file']['name'] as $key => $value) {
				if (!empty($_FILES['document_file']['name'][$key])) {
					$total_size += $_FILES['document_file']['size'][$key];
				}
			}

			if($total_size<(5 * 1024 * 1024)) {

				$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/writer/".$document_id."/";
				if(!file_exists($targetPath)) {
					@mkdir($targetPath, 0777);
				}

				$upload_files = [];
				foreach ($_FILES['document_file']['name'] as $key => $value) {
					if (!empty($_FILES['document_file']['name'][$key])) {

						$file_data = $_FILES['document_file']['name'][$key]; // файл основа
						$file_tmp = $_FILES['document_file']['tmp_name'][$key]; // изображения для данных
						$file_data = rename_file($file_data, $targetPath); // переименовываем

						$use_original_name = explode(".", $_FILES['document_file']['name'][$key]);
						array_pop($use_original_name);
						$use_original_name = implode(".", $use_original_name);

						if(mb_strlen($use_original_name) < \config::app('app_min_strlen')) {
							$use_new_name = explode(".", $file_data);
							$use_original_name = $use_new_name[0];
						}

						if ($file_data != false) {
							$targetFile =  $targetPath . $file_data;
							if(move_uploaded_file($file_tmp, $targetFile)) {
								$upload_files[] = $file_data;
							}
						}
					}
				}

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_writer
					SET
						document_file = '".implode(",",$upload_files)."'
					WHERE
						document_id = '".$document_id."'
				");
			}
			// загрузка файлов

			if($_SESSION['user_group']) {
				header('Location:'.HOST_NAME.'/translations/');
			} else {
				header('Location:'.HOST_NAME.'/#login');
			}
			exit;
		}

		$html = twig::fetch('frontend/chank/modal_search_writer.tpl');
		echo json_encode(array("title"=>$title, "html"=>$html, "status"=>"success"));
		exit;
	}

	static function writer_search() {
		if($_SESSION['user_group'] == 2) {
			$document_id = (int)$_REQUEST['void_id'];

			if($_REQUEST['search']) {
				$sql_where = "";
				if($_REQUEST['search_free'] == 1) {
					$sql_where .= " AND user_in_work != '1' ";
				}

				if($_REQUEST['search_online'] == 1) {
					$sql_where .= " AND user_visittime+600 > '".time()."' ";
				}

				$search_title = clear_text($_REQUEST['search_title']);
				if(mb_strlen($search_title)>=config::app('app_min_strlen')) {
					$sql_where .= " AND (user_firstname LIKE '%".$search_title."%' OR user_lastname LIKE '%".$search_title."%') ";
				}

				$user_list = DB::fetchrow("
					SELECT * FROM
						" . DB::$db_prefix . "_users
					WHERE
						user_status = '1' AND user_group = '3' $sql_where
					ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
					LIMIT 10
				");

				foreach ($user_list as $row) {
					$row = profile::profile_info($row);
				}

				twig::assign('user_list', $user_list);
				$html = twig::fetch('frontend/chank/search_writer_item.tpl');
				echo json_encode(array("title"=>$title, "html"=>$html, "status"=>"success"));
				exit;
			}

			twig::assign('document_id', $document_id);
			$html = twig::fetch('frontend/chank/search_writer.tpl');
			$title = twig::$lang['search_ispolnitel'];
			echo json_encode(array("title"=>$title, "html"=>$html, "status"=>"success"));
		}
		exit;
	}

	static function get_info_about_document($document_id) {
		$document_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_writer
			WHERE
				document_id = '".$document_id."'
		");

		if($document_info) {
			$document_info = self::document_info($document_info);
		}

		return $document_info;
	}

	static function search_main() {

		// title
		$page_info->page_title = twig::$lang['search_main_title'];
		twig::assign('page_info', $page_info);
		// title

		if(!$_SESSION['user_id']) {
			unset(twig::$lang['search_main_array_type'][4]);
		}

		if($_REQUEST['search_type'] && !array_key_exists($_REQUEST['search_type'], twig::$lang['search_main_array_type'])) {
			header('Location:'.ABS_PATH.'search');
			exit;
		}
		twig::assign('search_main_array_type', twig::$lang['search_main_array_type']);

		$search_type_active = (int)$_REQUEST['search_type'];
		$search_type_active = $search_type_active ? $search_type_active : 1;
		twig::assign('search_type_active', $search_type_active);

		$search = clear_text($_REQUEST['search']);

		switch ($search_type_active) {
			case '2':
				self::search_perfomens($search);
			break;

			case '3':
				self::search_jobs($search);
			break;

			case '4':
				self::search_resume($search);
			break;

			case '5':
				self::search_news($search);
			break;

			case '1':
			default:
				self::search_news($search, 4);
				self::search_perfomens($search, 4);
				self::search_jobs($search, 4);
				self::search_resume($search, 4);
			break;
		}

		$html = twig::fetch('frontend/chank/perfomens_col.tpl');
		twig::assign('perfomens_col', $html);

		twig::assign('content', twig::fetch('frontend/search_main.tpl'));
	}

	static function search_news($search, $slice = false) {

		// pager
		$limit = 10;
		$limit = ($slice !== false) ? $slice : $limit;
		$start = get_current_page() * $limit - $limit;
		// pager

		$news = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_module_page
			WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1' AND (page_title LIKE '%".$search."%' OR page_text LIKE '%".$search."%')
			ORDER BY page_id DESC
			LIMIT " . $start . "," . $limit . "
		");

		foreach ($news as $row) {
			$row = modules\page::page_info($row);
		}

		// pager
		if($slice === false) {
			$num = \DB::numrows("
				SELECT
					page_id
				FROM
					" . DB::$db_prefix . "_module_page
				WHERE page_folder = '2' AND page_lang = '".site::$lang."' AND page_status = '1' AND (page_title LIKE '%".$search."%' OR page_text LIKE '%".$search."%')
			");
			\twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME.'/search/type-5/?search='.$search.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
		}
		// pager

		twig::assign('news_list', $news);
	}

	static function search_perfomens($search, $slice = false) {

		// pager
		$limit = 10;
		$limit = ($slice !== false) ? $slice : $limit;
		$start = get_current_page() * $limit - $limit;
		// pager

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		$city_list = city_by_lang($_REQUEST['user_country']);
		twig::assign('city_list', $city_list);

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		// справочник по средствам связи
		$communication_list = get_book_for_essence(4);
		twig::assign('communication_list', $communication_list);
		// справочник по средствам связи

		// справочник по опыту работы
		$time_list = get_book_for_essence(8);
		twig::assign('time_list', $time_list);
		// справочник по опыту работы

		// справочник по опыту работы
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		// справочник по опыту работы

		$place_list = get_book_for_essence(17);
		twig::assign('place_list', $place_list);

		$theme_list = get_book_for_essence(2);
		twig::assign('theme_list', $theme_list);

		$level_list = get_book_for_essence(14);
		twig::assign('level_list', $level_list);

		$place_work_list = get_book_for_essence(17);
		twig::assign('place_work_list', $place_work_list);

		$sql = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users
			WHERE user_group = '3' AND user_status = '1' AND (user_login LIKE '%".$search."%' or user_firstname LIKE '%".$search."%' OR user_lastname LIKE '%".$search."%' OR user_email LIKE '%".$search."%' )
			ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
			LIMIT " . $start . "," . $limit . "
		");

		$perfomer_list = [];
		foreach ($sql as $row) {
			$row->user_default_lang = $lang_list[$row->user_default_lang];

			$row->city_list = city_by_lang($row->user_country);

			$perfomer_list[$row->id] = profile::profile_info($row);
			$perfomer_list_var[] = $row->id;
		}

		if($perfomer_list_var) {
			// языковые пары
			$lang_var = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_services
				WHERE serv_owner IN (".implode(",", $perfomer_list_var).")
			");
			foreach ($lang_var as $row) {
				$row->serv_lang_from = $lang_list[$row->serv_lang_from];
				$row->serv_lang_to = $lang_list[$row->serv_lang_to];

				$perfomer_list[$row->serv_owner]->lang_var[$row->serv_lang_from->id."_".$row->serv_lang_to->id] = $row;
			}
			// языковые пары
		}

		// pager
		if($slice === false) {
			$num = \DB::numrows("
				SELECT
					id
				FROM
					" . DB::$db_prefix . "_users
				WHERE user_group = '3' AND user_status = '1' AND (user_login LIKE '%".$search."%' or user_firstname LIKE '%".$search."%' OR user_lastname LIKE '%".$search."%' OR user_email LIKE '%".$search."%' )
			");
			\twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME.'/search/type-2/?search='.$search.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
		}
		// pager

		twig::assign('users_list', $perfomer_list);
	}

	static function search_jobs($search, $slice = false) {
		// pager
		$limit = 10;
		$limit = ($slice !== false) ? $slice : $limit;
		$start = get_current_page() * $limit - $limit;
		// pager

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		$city_list = city_by_lang($_REQUEST['user_country']);
		twig::assign('city_list', $city_list);

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		$lang_level_list = get_book_for_essence(14);
		twig::assign('lang_level_list', $lang_level_list);

		// справочник по опыту работы
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		// справочник по опыту работы

		$jobs_title = get_book_for_essence(21);
		twig::assign('jobs_title', $jobs_title);

		$sql = DB::fetchrow("
		SELECT * FROM
			" . DB::$db_prefix . "_users_jobs as jbs
		JOIN
			" . DB::$db_prefix . "_users as usr on jbs.jobs_owner = usr.id
			WHERE user_status = '1' AND user_group = '4' AND (jobs_desc LIKE '%".$search."%' OR jobs_company_title LIKE '%".$search."%' OR jobs_company_desc LIKE '%".$search."%')
			ORDER BY jobs_id DESC
			LIMIT " . $start . "," . $limit . "
		");

		$jobs_list = [];
		foreach ($sql as $row) {
			$row = siteJobs::jobs_info($row);

			$row->jobs_skill_lang = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs_lang
				WHERE job_id = '". $row->jobs_id ."'
			");
			foreach ($row->jobs_skill_lang as $key => $value) {
				$row->jobs_skill_lang_title[] = $lang_list[$value->lang_id]->title;
			}
			
			$jobs_list[$row->jobs_id] = profile::profile_info($row);
		}

		// pager
		if($slice === false) {
			$num = \DB::numrows("
			SELECT jobs_id FROM
				" . DB::$db_prefix . "_users_jobs as jbs
			JOIN
				" . DB::$db_prefix . "_users as usr on jbs.jobs_owner = usr.id
				WHERE user_status = '1' AND user_group = '4' AND (jobs_desc LIKE '%".$search."%' OR jobs_company_title LIKE '%".$search."%' OR jobs_company_desc LIKE '%".$search."%')
			");
			\twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME.'/search/type-3/?search='.$search.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
		}
		// pager

		twig::assign('jobs_list', $jobs_list);
	}

	static function search_resume($search, $slice = false) {

		if(!$_SESSION['user_id']) return false;

		// pager
		$limit = 10;
		$limit = ($slice !== false) ? $slice : $limit;
		$start = get_current_page() * $limit - $limit;
		// pager

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country

		$city_list = city_by_lang($_REQUEST['user_country']);
		twig::assign('city_list', $city_list);

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		$lang_level_list = get_book_for_essence(14);
		twig::assign('lang_level_list', $lang_level_list);

		// справочник по опыту работы
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		// справочник по опыту работы

		$jobs_title = get_book_for_essence(21);
		twig::assign('jobs_title', $jobs_title);

		$sql = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users_resume as resu
			JOIN
				" . DB::$db_prefix . "_users as usr on resu.resume_owner = usr.id
			WHERE user_status = '1' AND user_group = '3' AND (resume_lastname LIKE '%".$search."%' or resume_firstname LIKE '%".$search."%' OR resume_patronymic LIKE '%".$search."%' OR resume_dop_info_desc LIKE '%".$search."%')
			ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
			LIMIT " . $start . "," . $limit . "
		");

		$resume_list = [];
		foreach ($sql as $row) {
			$row->user_lang_default = $lang_list[$row->user_lang_default];
			$resume_list[$row->id] = siteResume::resume_info($row);
		}
		
		$resume_skill_lang = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_resume_lang
			WHERE resume_id = '". implode(",", array_keys($resume_list)) ."'
		");
		foreach ($resume_skill_lang as $key => $value) {
			$value->lang_id = $lang_list[$value->lang_id];
			$value->lang_level = $lang_level_list[$value->lang_level];

			$resume_list[$value->resume_id]->resume_skill_lang[] = $value;
			$resume_list[$value->resume_id]->resume_skill_lang_title[] = $value->lang_id->title;
		}

		// pager
		if($slice === false) {
			$num = \DB::numrows("
			SELECT resu.id FROM
				" . DB::$db_prefix . "_users_resume as resu
			JOIN
				" . DB::$db_prefix . "_users as usr on resu.resume_owner = usr.id
			WHERE user_status = '1' AND user_group = '3' AND (resume_lastname LIKE '%".$search."%' or resume_firstname LIKE '%".$search."%' OR resume_patronymic LIKE '%".$search."%')
			");
			\twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME.'/search/type-4/?search='.$search.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
		}
		// pager

		twig::assign('resume_list', $resume_list);
	}





	static function document_info($document_info) {
		$lang_list = get_book_for_essence(1);
		$theme_list = get_book_for_essence(2);
		$country_list = country_by_lang();

		$document_info->document_from_lang = $lang_list[$document_info->document_from_lang];
		$document_info->document_to_lang = $lang_list[$document_info->document_to_lang];
		$document_info->document_theme = $theme_list[$document_info->document_theme];
		$document_info->document_date = unix_to_date($document_info->document_date);

		if($document_info->document_way == 2) {
			$document_city_from = city_by_lang($document_info->document_country_from);
			$document_info->document_country_from = $country_list[$document_info->document_country_from];
			$document_info->document_city_from = $document_city_from[$document_info->document_city_from];
			$metro_list = get_book_for_essence(siteSearch::$metro[$document_info->document_city_from->id]);
			$document_info->document_metro_from = $metro_list[$document_info->document_metro_from];
		}

		if($document_info->document_way == 3) {
			$document_city_to = city_by_lang($document_info->document_country_to);
			$document_info->document_country_to = $country_list[$document_info->document_country_to];
			$document_info->document_city_to = $document_city_to[$document_info->document_city_to];
			$metro_list = get_book_for_essence(siteSearch::$metro[$document_info->document_city_to->id]);
			$document_info->document_metro_to = $metro_list[$document_info->document_metro_to];
		}

		if($document_info->document_way == 4) {
			$document_city_offline = city_by_lang($document_info->document_country_offline);
			$document_info->document_country_offline = $country_list[$document_info->document_country_offline];
			$document_info->document_city_offline = $document_city_offline[$document_info->document_city_offline];
		}

		$document_info->document_owner = get_user_info($document_info->document_owner);

		$document_info->document_file = explode(",", $document_info->document_file);

		return $document_info;
	}

}

?>
