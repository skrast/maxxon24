<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteOrder {

	static $book_link = [
		1 => 6,
		2 => 15,
		3 => 16,
	];
	static $default_book_link = 6;

	static $limit_order_on_edit_list = 5;

	static function order_work() {
		// title
		$page_info->page_title = twig::$lang['order_title'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 4) {
			site::error404();
		} else {

			$order_id = (int)$_REQUEST['order_id'];
			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_owner = '". $profile_id ."' AND order_id = '".$order_id."' AND order_close != '1' AND order_perfomens = '0' AND order_delete != '1'
			");

			if($_REQUEST['save']) {
				self::saveOrder($order_id);
			}

			if($order_id && !$order_info) {
				site::error404();
			} else {
				$order_info = self::order_info($order_info);
				twig::assign('order_info', $order_info);

				// country
				$country_list = country_by_lang();
				twig::assign('country_list', $country_list);
				// country

				$city_list = city_by_lang($order_info->order_country);
				twig::assign('city_list', $city_list);

				// справочник по языкам
				$lang_list = get_book_for_essence(1);
				twig::assign('lang_list', $lang_list);
				// справочник по языкам

				$default = ($order_info->order_skill) ? self::$book_link[$order_info->order_skill] : self::$default_book_link;
				$service_list = get_book_for_essence($default);
				twig::assign('service_list', $service_list);

				$currency_list = get_book_for_essence(5);
				twig::assign('currency_list', $currency_list);

				$level_list = get_book_for_essence(14);
				twig::assign('level_list', $level_list);

				twig::assign('book_link', self::$book_link);

				$theme_list = get_book_for_essence(2);
				twig::assign('theme_list', $theme_list);

				$time_list = get_book_for_essence(8);
				twig::assign('time_list', $time_list);

				// work
				$order_work = DB::fetchrow("
					SELECT *
					FROM " . DB::$db_prefix . "_users_order
					WHERE order_owner = '". $profile_id ."' AND order_status = '1'  AND order_close != '1' AND order_perfomens = '0' AND order_delete != '1'
					ORDER BY order_edit DESC, order_add DESC
					LIMIT 0, ".self::$limit_order_on_edit_list."
				");

				foreach ($order_work as $value) {
					$value = self::order_info($value);
				}
				twig::assign('order_work', $order_work);
				// work

				// draft
				$order_draft = DB::fetchrow("
					SELECT *
					FROM " . DB::$db_prefix . "_users_order
					WHERE order_owner = '". $profile_id ."' AND order_status != '1'  AND order_close != '1' AND order_delete != '1'
					ORDER BY order_edit DESC, order_add DESC
					LIMIT 0, ".self::$limit_order_on_edit_list."
				");
				foreach ($order_draft as $value) {
					$value = self::order_info($value);
				}
				twig::assign('order_draft', $order_draft);
				// draft

				// close
				$order_close = DB::fetchrow("
					SELECT *
					FROM " . DB::$db_prefix . "_users_order
					WHERE order_owner = '". $profile_id ."' AND order_close = '1' AND order_delete != '1'
					ORDER BY order_edit DESC, order_add DESC
					LIMIT 0, ".self::$limit_order_on_edit_list."
				");
				foreach ($order_close as $value) {
					$value = self::order_info($value);
				}
				twig::assign('order_close', $order_close);
				// close

				// шаблон страницы
				$html = twig::fetch('frontend/chank/perfomens_col.tpl');
				twig::assign('perfomens_col', $html);
				twig::assign('content', twig::fetch('frontend/owner_order_edit.tpl'));
			}
		}
	}

	static function saveOrder($order='') {
		$profile_id = (int)$_SESSION['user_id'];
		$order_id = (int)$_REQUEST['order_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 4) {
			site::error404();
		}

		$error = [];

	
		$default = ($_REQUEST['order_service']) ? self::$book_link[$_REQUEST['order_service']] : 6;
		$service_list = get_book_for_essence($default);
		try {
			v::key((int)$_REQUEST['order_service'])->assert($service_list);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_service_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		try {
			v::intVal()->notEmpty()->assert($_REQUEST['order_lang_from']);
			v::intVal()->notEmpty()->assert($_REQUEST['order_lang_to']);
			v::not(v::identical((int)$_REQUEST['order_lang_from']))->assert((int)$_REQUEST['order_lang_to']);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_lang_from_to_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		try {
			v::intVal()->notEmpty()->assert((int)$_REQUEST['order_country']);
			v::intVal()->notEmpty()->assert((int)$_REQUEST['order_city']);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_country_city_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		try {
			v::key((int)$_REQUEST['order_skill'])->assert(self::$book_link);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_skill_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		try {
			v::intVal()->notEmpty()->assert(date_to_unix($_REQUEST['order_start']));
			v::intVal()->notEmpty()->assert(date_to_unix($_REQUEST['order_end']));
			v::not(v::identical(date_to_unix($_REQUEST['order_start'])))->assert(date_to_unix($_REQUEST['order_end']));
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_start_end_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		/*try {
			v::length(config::app('app_min_strlen'), null)->assert(clear_text($_REQUEST['order_desc']));
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["order_desc_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}*/

		if(format_string_number($_REQUEST['order_budget_start'], true) > format_string_number($_REQUEST['order_budget_end'], true) || format_string_number($_REQUEST['order_budget_start'], true) < 0) {
			$error[] = twig::$lang["order_budget_error"];

			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		

		/*if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}*/

		if(backend::isAjax()) {
			echo json_encode(array("upload"=>true, "status"=>"success"));
			exit;
		}

		if($_REQUEST['order_skill'] != 2) {
			$_REQUEST['order_level'] = 0;
		}

		if($order) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_order
				SET
					order_desc = '". clear_text($_REQUEST['order_desc'])."',
					order_dress_desc = '". clear_text($_REQUEST['order_dress_desc'])."',

					order_country = '". (int)$_REQUEST['order_country']."',
					order_city = '". (int)$_REQUEST['order_city']."',

					order_budget_start = '". format_string_number($_REQUEST['order_budget_start'], true)."',
					order_budget_end = '". format_string_number($_REQUEST['order_budget_end'], true)."',
					order_currency = '". (int)$_REQUEST['order_currency']."',

					order_status = '".($_REQUEST['order_status'] == 1 ? 1 : 0)."',
					order_dress = '".($_REQUEST['order_dress'] == 1 ? 1 : 0)."',

					order_start = '". date_to_unix_time($_REQUEST['order_start'])."',
					order_end = '". date_to_unix_time($_REQUEST['order_end'])."',

					order_skill = '". (int)$_REQUEST['order_skill']."',
					order_service = '". (int)$_REQUEST['order_service']."',
					order_lang_from = '". (int)$_REQUEST['order_lang_from']."',
					order_lang_to = '". (int)$_REQUEST['order_lang_to']."',
					order_level = '". (int)$_REQUEST['order_level']."',

					order_theme = '". (int)$_REQUEST['order_theme']."',
					order_currency_time = '". (int)$_REQUEST['order_currency_time']."',

					order_edit = '".time()."'

				WHERE order_owner = '". $_SESSION['user_id'] ."' AND order_id = '".$order_id."' AND order_delete != '1'
			");

			$respons = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_respons as rsp
				JOIN " . DB::$db_prefix . "_users as usr on usr.id = rsp.response_owner
				WHERE response_work = '". $order_id ."' AND user_status = '1' AND user_group = '3'
			");

			foreach ($respons as $value) {
				$message = twig::$lang['message_order_edit'] . "ID №".$order_id;
				siteMessage::message_add($_SESSION['user_id'], $value->respons_owner, $message);
			}

		} else {
			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_order
				SET
					order_desc = '". clear_text($_REQUEST['order_desc'])."',
					order_dress_desc = '". clear_text($_REQUEST['order_dress_desc'])."',

					order_country = '". (int)$_REQUEST['order_country']."',
					order_city = '". (int)$_REQUEST['order_city']."',

					order_budget_start = '". format_string_number($_REQUEST['order_budget_start'], true)."',
					order_budget_end = '". format_string_number($_REQUEST['order_budget_end'], true)."',
					order_currency = '". (int)$_REQUEST['order_currency']."',

					order_status = '".($_REQUEST['order_status'] == 1 ? 1 : 0)."',
					order_dress = '".($_REQUEST['order_dress'] == 1 ? 1 : 0)."',

					order_start = '". date_to_unix_time($_REQUEST['order_start'])."',
					order_end = '". date_to_unix_time($_REQUEST['order_end'])."',

					order_skill = '". (int)$_REQUEST['order_skill']."',
					order_service = '". (int)$_REQUEST['order_service']."',
					order_lang_from = '". (int)$_REQUEST['order_lang_from']."',
					order_lang_to = '". (int)$_REQUEST['order_lang_to']."',
					order_level = '". (int)$_REQUEST['order_level']."',

					order_theme = '". (int)$_REQUEST['order_theme']."',
					order_currency_time = '". (int)$_REQUEST['order_currency_time']."',

					order_add = '".time()."',
					order_edit = '".time()."',

					order_owner = '". $_SESSION['user_id'] ."'
			");

			$order_id = DB::lastInsertId();
		}

		if($_REQUEST['order_status'] == 1) {
			header('Location:'.HOST_NAME.'/order-'.$order_id.'/');
		} else {
			header('Location:'.HOST_NAME.'/order/edit-'.$order_id.'/');
		}

		exit;
	}

	static function order_open() {
		$order_id = (int)$_REQUEST['order_id'];

		$order_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_order as ord
			JOIN " . DB::$db_prefix . "_users as usr on ord.order_owner = usr.id
			WHERE order_id = '". $order_id ."' AND order_delete != '1'
		");

		if(!$order_info || ($order_info->order_status == 0 && $order_info->order_owner != $_SESSION['user_id'])) {
			site::error404();
		} else {

			if($_REQUEST['change_perfomens'] && $_SESSION['user_id'] == $order_info->order_owner) {
				// выбор исполнителя заказчиком

				$user_info = get_user_info($_REQUEST['change_perfomens']);

				if($user_info && $user_info->user_group == 3 && $user_info->user_status == 1 && $order_info->order_close != 1) {
					/*DB::query("
						UPDATE " . DB::$db_prefix . "_users_order
						SET
							order_perfomens = '".(int)$_REQUEST['change_perfomens']."'
						WHERE order_id = '". $order_id ."'
					");*/

					DB::query("
						INSERT INTO " . DB::$db_prefix . "_users_response
						SET
							response_to = '".(int)$_REQUEST['change_perfomens']."',
							response_perfomens = '".(int)$_REQUEST['change_perfomens']."',
							response_from = '".$_SESSION['user_id']."',
							response_add = '".time()."',
							response_work = '".$order_id."',
							response_type = '2'
					");
					$respons_id = DB::lastInsertId();

					/*$message = twig::$lang['message_order_owner_change_perfomens'] . "ID №".$order_id;
					siteMessage::message_add($_SESSION['user_id'], $user_info->id, $message);*/

					siteBot::order_owner_change_perfomens((int)$_REQUEST['change_perfomens'], $respons_id);

					echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
					exit;
				}

				exit;
			}

			$order_info = self::order_info($order_info);
			$order_info = profile::profile_info($order_info);

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			twig::assign('order_info', $order_info);

			// title
			$page_info->page_title = twig::$lang['order_page'] . " №".$order_info->order_id;
			twig::assign('page_info', $page_info);
			// title

			if($order_info->order_owner == $_SESSION['user_id']) {
				$friends_list = DB::fetchrow("
					SELECT *
					FROM " . DB::$db_prefix . "_users_friends as fr
					JOIN " . DB::$db_prefix . "_users as usr on usr.id = fr.friends_to
					WHERE friends_owner = '". $order_info->order_owner ."' AND user_status = '1'
					ORDER BY friends_date DESC
				");

				foreach ($friends_list as $row) {
					$row = profile::profile_info($row);
				}
				twig::assign('friends_list', $friends_list);
			}

			if($_SESSION['user_group'] == 3) {
				$is_respons = DB::single("
					SELECT 1
					FROM " . DB::$db_prefix . "_users_response
					WHERE response_perfomens = '". $_SESSION['user_id'] ."' AND response_work = '". $order_info->order_id ."' AND respons_to = '".$_SESSION['user_id']."'
				");
				twig::assign('is_respons', $is_respons);
			}

			if($order_info->order_perfomens) {
				$check_otziv = DB::row("
					SELECT otziv_id FROM
						" . DB::$db_prefix . "_users_otziv
					WHERE
						otziv_owner = '".$_SESSION['user_id']."' AND otzive_order = '".$order_info->order_id."'
				");

				twig::assign('check_otziv', $check_otziv);
			}

			// шаблон страницы
			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);
			twig::assign('content', twig::fetch('frontend/owner_order_open.tpl'));
		}
	}

	static function order_status() {
		$order_id = (int)$_REQUEST['order_id'];
		$order_status = (int)$_REQUEST['order_status'];
		$user_id = (int)$_SESSION['user_id'];

		$order_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_order as ord
			JOIN " . DB::$db_prefix . "_users as usr on ord.order_owner = usr.id
			WHERE order_id = '". $order_id ."'
		");

		if($order_info->order_perfomens == 0) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_order
				SET
					order_status = '".($order_status == 1 ? 1 : 0)."',
					order_edit = '".time()."'
				WHERE order_owner = '". $user_id ."' AND order_id = '".$order_id."' AND order_delete != '1'
			");

			if($order_status != 1) { // уведомление об отмене заказа

				$respons = DB::fetchrow("
					SELECT * FROM
						" . DB::$db_prefix . "_users_respons as rsp
					JOIN " . DB::$db_prefix . "_users as usr on usr.id = rsp.response_owner
					WHERE response_work = '". $order_id ."' AND user_status = '1' AND user_group = '3'
				");

				foreach ($respons as $value) {
					$message = twig::$lang['message_order_in_draft'] . "ID №".$order_id;
					siteMessage::message_add($_SESSION['user_id'], $value->respons_owner, $message);
				}

			}
		}

		header('Location:'.HOST_NAME.'/order-'.$order_id.'/');
		exit;
	}

	static function order_close() {
		$order_id = (int)$_REQUEST['order_id'];
		$user_id = (int)$_SESSION['user_id'];

		$order_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_order
			WHERE order_id = '".$order_id."' AND order_close != '1' AND order_perfomens != '0' AND order_delete != '1'
		");

		if($order_info && $order_info->order_perfomens) {

			if($_SESSION['user_group'] == 4) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_close = '1',
						order_edit = '".time()."'
					WHERE order_owner = '". $user_id ."' AND order_id = '".$order_id."'
				");
			}

			if($_SESSION['user_group'] == 3) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_close = '1',
						order_edit = '".time()."'
					WHERE order_id = '".$order_id."' AND order_perfomens = '".$_SESSION['user_id']."'
				");
			}

			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_work = '".$order_id."' AND response_perfomens = '".$order_info->order_perfomens."'
			");

			siteBot::order_owner_close($order_info->order_perfomens, $respons_info->response_id);
			siteBot::order_perfomens_close($order_info->order_owner, $respons_info->response_id);
		}

		header('Location:'.HOST_NAME.'/order-'.$order_id.'/');
		exit;
	}

	static function order_accept() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 3) {
			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_perfomens = '".$user_id."' AND response_to = '".$_SESSION['user_id']."'
			");

			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_id = '".$respons_info->response_work."' AND order_close != '1' AND order_perfomens = '0' AND order_delete != '1'
			");

			if($order_info && $respons_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_perfomens = '".$user_id."'
					WHERE order_id = '".$order_id."'
				");

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_perfomens_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::order_perfomens_owner_accept($order_info->order_owner, $respons_id);

				//siteBot::order_perfomens_accept($order_info->order_owner, $order_id, $user_id);

				//$message = twig::$lang['message_order_in_work'] . "ID №".$order_id;
				//siteMessage::message_add($_SESSION['user_id'], $order_info->order_owner, $message);
			}
		}

		if($_SESSION['user_group'] == 4) {

			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."'
			");

			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_id = '".$respons_info->response_work."' AND order_close != '1' AND order_perfomens = '0' AND order_delete != '1' AND order_owner = '".$_SESSION['user_id']."'
			");

			if($order_info && $respons_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_perfomens = '".$respons_info->response_perfomens."'
					WHERE order_id = '".$order_info->order_id."'
				");

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::order_owner_perfomens_accept($respons_info->response_perfomens, $respons_id);

				/*$message = twig::$lang['message_order_accept_in_work'] . "ID №".$order_id;
				siteMessage::message_add($_SESSION['user_id'], $order_info->response_perfomens, $message);*/
			}
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/order-'.$order_info->order_id.'/');
		}
		exit;
	}

	static function order_deny() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];


		if($_SESSION['user_group'] == 3) { // исполнитель

			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_perfomens = '".$user_id."'
			");

			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_id = '".$respons_info->response_work."' AND order_close != '1' AND order_delete != '1'
			");

			if($order_info && ($respons_info || $order_info->order_perfomens == $user_id)) {
				/*DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_response
					WHERE response_work = '".$respons_info->response_work."' AND response_perfomens = '".$user_id."'
				");*/

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_perfomens_not_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				/*DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_perfomens = '0'
					WHERE order_id = '".$respons_info->response_work."'
				");*/

				/*$message = twig::$lang['message_order_in_deny'] . " ID №".$respons_info->response_work;
				siteMessage::message_add($_SESSION['user_id'], $order_info->order_owner, $message);*/
			}
		}

		if($_SESSION['user_group'] == 4) { // заказчик

			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."'
			");

			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_id = '".$respons_info->response_work."' AND order_close != '1' AND order_delete != '1'
			");

			if($order_info && $respons_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_not_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				/*$message = twig::$lang['message_order_in_deny_from_owner'] . " ID №".$respons_info->response_work;
				siteMessage::message_add($_SESSION['user_id'], $respons_info->response_from, $message);*/
			}
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/order-'.$respons_info->response_work.'/');
		}

		exit;
	}

	static function order_respons() {
		$order_id = (int)$_REQUEST['order_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 3) {
			$order_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_order
				WHERE order_id = '".$order_id."' AND order_close != '1' AND order_perfomens = '0' AND order_delete != '1'
			");

			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_work = '".$order_id."' AND response_perfomens = '".$user_id."' AND response_from = '".$user_id."'
			");

			if($order_info && !$respons_info) {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_response
					SET
						response_type = '2',
						response_to = '".$order_info->order_owner."',
						response_from = '".$user_id."',
					 	response_work = '".$order_id."',
						response_perfomens = '".$user_id."',
						response_add = '".time()."'
				");
				$respons_id = DB::lastInsertId();

				siteBot::order_perfomens_accept($order_info->order_owner, $respons_id);
				/*$message = twig::$lang['message_order_quest_accept'] . " ID №".$order_id;
				siteMessage::message_add($_SESSION['user_id'], $order_info->order_owner, $message);*/
			}
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/order-'.$order_id.'/');
		}

		exit;
	}

	static function order_delete() {
		$order_id = (int)$_REQUEST['order_id'];
		$user_id = (int)$_SESSION['user_id'];

		DB::query("
			UPDATE
				" . DB::$db_prefix . "_users_order
			SET
				order_delete = '1'
			WHERE order_owner = '". $user_id ."' AND order_id = '".$order_id."'
		");

		header('Location:'.HOST_NAME.'/order/');
		exit;
	}

	static function bild_order_search_filter() {
		// поиск
		$page_link = "";
		$sql_link = "";


		/*if(!$_REQUEST['order_owner'] && $_REQUEST['filter']) {
			$sql_link .= " AND order_perfomens = '0' AND order_close != '1' AND order_delete != '1' ";
		}*/

		if(!empty($_REQUEST['order_country'])) {
			$sql_link .= " AND order_country = '".(int)$_REQUEST['order_country']."' ";
			$page_link .= "&order_country=".(int)$_REQUEST['order_country'];
		}

		if(!empty($_REQUEST['order_city'])) {
			$sql_link .= " AND order_city = '".(int)$_REQUEST['order_city']."' ";
			$page_link .= "&order_city=".(int)$_REQUEST['order_city'];
		}

		if(!empty($_REQUEST['order_skill'])) {
			$sql_link .= " AND order_skill IN (". addslashes(implode(",", $_REQUEST['order_skill'])).") ";

			foreach ($_REQUEST['order_skill'] as $key => $value) {
				$page_link .= "&order_skill[]=".(int)$value;
			}
		}

		if(!empty(date_to_unix($_REQUEST['order_start'])) && !empty(date_to_unix($_REQUEST['order_end'])) && date_to_unix($_REQUEST['order_start'])<date_to_unix($_REQUEST['order_end'])) {
			$sql_link .= " AND order_start >= '".date_to_unix($_REQUEST['order_start'])."' AND order_end <= '".date_to_unix($_REQUEST['order_end'])."'  ";

			$page_link .= "&order_start=".urlencode(clear_text($_REQUEST['order_start']))."&order_end=".urlencode(clear_text($_REQUEST['order_end']));
		}

		if(!empty($_REQUEST['order_lang_from']) && !empty($_REQUEST['order_lang_to']) && $_REQUEST['order_lang_from']!=$_REQUEST['order_lang_to']) {
			$sql_link .= " AND order_lang_from = '".(int)$_REQUEST['order_lang_from']."' AND order_lang_to = '".(int)$_REQUEST['order_lang_to']."' ";

			$page_link .= "&order_lang_from=".(int)$_REQUEST['order_lang_from']."&order_lang_to=".(int)$_REQUEST['order_lang_to'];
		}

		if($_SESSION['user_group'] == 3) {

			if($_REQUEST['order_respons'] == 1) {
				$sql_link .= " AND order_id IN (SELECT response_work FROM " . DB::$db_prefix . "_users_response as rsp WHERE response_from = '". $_SESSION['user_id'] ."')";
				$page_link .= "&order_respons=1";
			}
			
			if($_REQUEST['order_status'] == 1) {
				$sql_link .= " AND order_perfomens = '". $_SESSION['user_id'] ."' AND order_close != '1' AND order_delete != '1' ";
				$page_link .= "&order_status=1";
			}
			if($_REQUEST['order_close'] == 1) {
				$sql_link .= " AND order_perfomens = '". $_SESSION['user_id'] ."' AND order_close = '1' AND order_delete != '1' ";
				$page_link .= "&order_close=1";
			} else {
				$sql_link .= " AND order_status = '1' AND order_delete != '1' ";
			}

		}

		if($_SESSION['user_group'] == 4) {
			if($_REQUEST['order_owner'] == 1) {
				$sql_link .= " AND order_owner = '". $_SESSION['user_id'] ."' ";
				$page_link .= "&order_owner=1";
			}
			if($_REQUEST['order_status'] == 1) {
				$sql_link .= " AND order_owner = '". $_SESSION['user_id'] ."' AND order_status = '1' ";
				$page_link .= "&order_status=1";
			}
			if($_REQUEST['order_status'] == 2) {
				$sql_link .= " AND order_owner = '". $_SESSION['user_id'] ."' AND order_status != '1' ";
				$page_link .= "&order_status=2";
			}
			if($_REQUEST['order_close'] == 1) {
				$sql_link .= " AND order_owner = '". $_SESSION['user_id'] ."' AND order_close = '1' AND order_delete != '1' ";
				$page_link .= "&order_close=1";
			}
		}

		return array($page_link, $sql_link);
	}

	static function order_search() {

		$profile_id = (int)$_SESSION['user_id'];

		$profile_info = get_user_info($profile_id);

		if(!$profile_info) {
			site::error404();
		} else {

			// title
			$page_info->page_title = twig::$lang['order_list'];
			twig::assign('page_info', $page_info);
			// title

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			$city_list = city_by_lang($_REQUEST['order_country']);
			twig::assign('city_list', $city_list);

			// справочник по языкам
			$lang_list = get_book_for_essence(1);
			twig::assign('lang_list', $lang_list);
			// справочник по языкам

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// сборка параметров для поиска
			$bild_filter = self::bild_order_search_filter();
			$page_link = $bild_filter[0];
			$sql_link = $bild_filter[1];
			// сборка параметров для поиска

			$limit = 5;
			// pager
			$start = get_current_page() * $limit - $limit;
			// pager

			$order_list = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_order as ord
				JOIN
					" . DB::$db_prefix . "_users as usr on ord.order_owner = usr.id
				WHERE user_status = '1' AND user_group = '4' $sql_link
				ORDER BY order_edit DESC
				LIMIT " . $start . "," . $limit . "
			");

			foreach ($order_list as $row) {
				if($row->order_perfomens) {
					$row->order_perfomens = get_user_info($row->order_perfomens);
				}

				$row = self::order_info($row);
			}
			twig::assign('order_list', $order_list);

			// pager
			$num = DB::numrows("
				SELECT * FROM
					" . DB::$db_prefix . "_users_order as ord
				JOIN
					" . DB::$db_prefix . "_users as usr on ord.order_owner = usr.id
				WHERE user_status = '1' AND user_group = '4' $sql_link
			");

			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/bank_zakazov/?filter=1". $page_link.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/order_list.tpl'));
		}
	}

	static function get_info_about_order($order_id) {
		$order_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_order
			WHERE order_id = '".$order_id."'
		");

		if($order_info) {
			$order_info = self::order_info($order_info);
		}

		return $order_info;
	}

	static function order_info($order_info) {
		$lang_list = get_book_for_essence(1);
		$currency_list = get_book_for_essence(5);
		$level_list = get_book_for_essence(14);
		$theme_list = get_book_for_essence(2);
		$time_list = get_book_for_essence(8);

		$city_list = city_by_lang($order_info->order_country);
		$order_info->order_city = $city_list[$order_info->order_city];

		$default = ($order_info->order_skill) ? self::$book_link[$order_info->order_skill] : 6;
		$service_list = get_book_for_essence($default);

		$order_info->order_start_time = unix_to_date_time($order_info->order_start);
		$order_info->order_end_time = unix_to_date_time($order_info->order_end);

		$order_info->order_add = unix_to_date($order_info->order_add);
		$order_info->order_edit = unix_to_date($order_info->order_edit);
		$order_info->order_start = unix_to_date($order_info->order_start);
		$order_info->order_end = unix_to_date($order_info->order_end);

		$order_info->order_lang_from = $lang_list[$order_info->order_lang_from];
		$order_info->order_lang_to = $lang_list[$order_info->order_lang_to];
		$order_info->order_theme = $theme_list[$order_info->order_theme];
		$order_info->order_currency_time = $time_list[$order_info->order_currency_time];

		$order_info->order_currency = $currency_list[$order_info->order_currency];
		$order_info->order_level = $level_list[$order_info->order_level];
		$order_info->order_service = $service_list[$order_info->order_service];

		return $order_info;
	}

}

?>
