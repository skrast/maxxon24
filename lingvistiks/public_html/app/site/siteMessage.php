<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteMessage {
	static $max_message_on_chat = 5;
	static $max_owner_on_chat = 5;

	static function message_search() {
		$profile_id = (int)$_SESSION['user_id'];

		$profile_info = get_user_info($profile_id);

		if(!$profile_info) {
			site::error404();
		} else {

			if($_REQUEST['save']) {
				self::send_message();
			}

			if($_REQUEST['otziv']) {
				self::send_otziv();
			}

			if($_REQUEST['accept_perfomens_document']) {
				self::send_accept_perfomens();
			}

			if($_REQUEST['accept_perfomens']) {
				self::accept_perfomens();
			}

			$search_message = clear_text($_REQUEST['search_message']);

			// title
			$page_info->page_title = twig::$lang['message_title'];
			twig::assign('page_info', $page_info);
			// title

			// список пользователей
			$user_list = self::list_user_message($search_message);
			twig::assign('user_message_list', $user_list);
			// список пользователей

			$user_id = (int)$_REQUEST['message_to'];
			if($user_id && $user_id != $_SESSION['user_id']) {
				$profile_info = get_user_info($profile_id);
				$from_info = get_user_info($user_id);

				if($profile_info) {

					/*$order_id = (int)$_REQUEST['order_id'];
					if($order_id) {
						$order_info = DB::row("
							SELECT *
							FROM " . DB::$db_prefix . "_users_order
							WHERE order_id = '".$order_id."' AND order_status = '1' AND order_close = '1' AND order_perfomens != '0' AND order_delete != '1' AND ((order_owner = '".$_SESSION['user_id']."' AND order_accept != '1') OR (order_perfomens = '".$_SESSION['user_id']."' AND order_accept = '1'))
						");

						$check = DB::row("
							SELECT otziv_id FROM
								" . DB::$db_prefix . "_users_otziv
							WHERE
								otziv_to_id = '".$user_id."' AND otziv_owner = '".$_SESSION['user_id']."' AND otzive_order = '".$order_id."'
						");

						if($order_info && !$check) {
							twig::assign('order_info', $order_info);
						}
					}*/


					$user_search = "
						AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."')
						AND (message_from = '".$user_id."' OR message_to = '".$user_id."')
					";

					if($_REQUEST['message_to'] == siteBot::$bot_id) {
						$user_search = "
							AND (message_from = '".siteBot::$bot_id."' AND message_to = '".$_SESSION['user_id']."')
						";
					}

					// pager
					$limit = self::$max_message_on_chat*2;
					$count_all = DB::numrows("
						SELECT * FROM
							" . DB::$db_prefix . "_message as msg
						JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
						JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
						WHERE usr.user_status = '1' $user_search
					");
					// pager

					$message_list = DB::fetchrow("
						SELECT * FROM
							" . DB::$db_prefix . "_message as msg
						JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
						JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
						WHERE usr.user_status = '1' $user_search
						ORDER BY message_date DESC
						LIMIT ". $limit
					);

					$currency_list = get_book_for_essence(5);
					twig::assign('currency_list', $currency_list);

					$message_list = array_reverse($message_list, true);
					foreach ($message_list as $row) {

						if($row->message_parent) {
							$row->message_parent = DB::row("
								SELECT * FROM
									" . DB::$db_prefix . "_message as msg
								JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
								JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
								WHERE message_id = '".$row->message_parent."'
							");
							$row->message_parent = self::get_info_about_message($row->message_parent);
						}

						$row = self::get_info_about_message($row);
					}

					twig::assign('profile_info', $profile_info);
					twig::assign('from_info', $from_info);
					twig::assign('message_list', $message_list);
					twig::assign('send_message_to', $user_id);
					twig::assign('count_all', $count_all);

					// отметка о прочтении
					DB::query("
						UPDATE
							" . DB::$db_prefix . "_message
						SET message_open = '1'
						WHERE message_from = '".$user_id."'
					");

				}
			}

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			twig::assign('max_message_on_chat', self::$max_message_on_chat);
			twig::assign('max_owner_on_chat', self::$max_owner_on_chat);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/message.tpl'));
		}
	}

	static function list_user_message($search_message = "") {
		if($search_message) {
			$search_message = "
				AND (usr1.user_firstname LIKE '%".clear_text($search_message)."%' OR usr1.user_lastname LIKE '%".clear_text($search_message)."%')
			";
		}

		$sql = DB::fetchrow("
			SELECT msg.*,
			usr.user_firstname as from_user_firstname,
			usr.user_lastname as from_user_lastname,
			usr.user_login as from_user_login,
			usr.user_photo as from_user_photo,
			usr.user_group as from_user_group,
			usr1.user_firstname as to_user_firstname,
			usr1.user_lastname as to_user_lastname,
			usr1.user_photo as to_user_photo
			FROM
				" . DB::$db_prefix . "_message as msg
			JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
			JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
			WHERE usr.user_status = '1' AND usr1.user_status = '1' AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."') $search_message
			ORDER BY message_date DESC
		");

		$user_list = [];
		foreach ($sql as $row) {

			$row->message_date = unix_to_date($row->message_date);
			$row->message_user->user_name = ($row->message_to == $_SESSION['user_id']) ? get_username($row->from_user_firstname, $row->from_user_lastname, $row->from_user_login) : get_username($row->to_user_firstname, $row->to_user_lastname, $row->from_user_login);
			$row->message_user->id = ($row->message_to == $_SESSION['user_id']) ? $row->message_from : $row->message_to;
			$row->message_user->user_photo = ($row->message_to == $_SESSION['user_id']) ? $row->from_user_photo : $row->to_user_photo;
			$row->message_user->user_photo = $row->message_user->user_photo ? $row->message_user->user_photo : "no-avatar.png";

			if(!$user_list[$row->message_user->id]) {
				$row->unseen = 0;
				$user_list[$row->message_user->id] = $row;
			}

			if($user_list[$row->message_user->id]) {
				if($row->message_open != 1 && $row->message_to == $_SESSION['user_id']) {
					$user_list[$row->message_user->id]->unseen += 1;
				}

				$row->unseen = $user_list[$row->message_user->id]->unseen;
			}
		}

		foreach ($user_list as $row) {
			twig::assign('user', $row);
			$user_list[$row->message_user->id]->message_html = twig::fetch('frontend/chank/list_user_message.tpl');
		}


		return $user_list;
	}

	static function send_message() {
		$error = [];

		if($_REQUEST['no_uploads'] == 1) {
			self::message_upload($_SESSION['user_id']);
		}

		$user_id = (int)$_REQUEST['message_to'];
		$parent_id = (int)$_REQUEST['parent_id'];
		$message = clear_text($_REQUEST['message_desc']);

		if($_REQUEST['need_text']!=1) {
			try {
				v::length(config::app('app_min_strlen'), null)->assert($message);
			} catch(ValidationException $exception) {
				$error[] = twig::$lang["message_desc_error"];
			}
		}

		if($_REQUEST['need_text'] && !$_REQUEST['has_file']) {
			$error[] = twig::$lang["message_photo_error"];
		}

		$profile_info = get_user_info($user_id);
		if(!$profile_info || $user_id == $_SESSION['user_id'] || $profile_info->user_group_info->user_group == 5) {
			$error[] = twig::$lang["message_to_error"];
		}

		if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		/*if(backend::isAjax()) {
			echo json_encode(array("upload"=>true, "status"=>"success"));
			exit;
		}*/

		if($parent_id) {
			$message_check = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_message as msg
				JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
				JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
				WHERE usr.user_status = '1'
					AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."')
					AND message_id = '".$parent_id."'
			");

			if(!$message_check) {
				exit;
			}
		}

		self::message_add($_SESSION['user_id'], $user_id, $message, ["parent_id" => $parent_id]);

		echo json_encode(array("respons"=>twig::$lang['message_send_success'], "status"=>"success"));
		exit;
	}

	static function message_add($from, $to, $message, $param = '') {
		
		$sql = "";
		if($param['document_id']) {
			$sql .= "
				message_document = '".(int)$param['document_id']."',
			";
		}
		if(isset($param['parent_id'])) {
			$sql .= "
				message_parent = '".(int)$param['parent_id']."',
			";
		}
		if($param['message_respons']) {
			$sql .= "
				message_respons = '".(int)$param['message_respons']."',
				message_type = '".(int)$param['type']."',
			";
		} else {
			if($param['type']) {
				$sql .= "
					message_type = '".(int)$param['type']."',
				";
			} else {
			    $sql .= "
					message_type = '0',
				";
			}
		}

		DB::query("
			INSERT INTO
				" . DB::$db_prefix . "_message
			SET
				$sql
				message_from = '".$from."',
				message_to = '".$to."',
				message_desc = '".$message."',
				message_date = '".time()."'
		");
		$message_id = DB::lastInsertId();

		self::message_upload($message_id);

		/*$user_info = DB::row("SELECT * FROM " . DB::$db_prefix . "_users WHERE user_status = '1' AND id = '".$to."'");
		$user_info = profile::profile_info($user_info);

		twig::assign('user_info', $user_info);
		twig::assign('message', $message);
		$mail_body = twig::fetch('message_add.tpl');

		sendmail::sendmail_bilder($user_info, twig::$lang['message_user_writer_subject'], $mail_body, true, false);*/

		return frue;
	}

	static function load_new_message() {

		set_time_limit(0);

		while (true) {
			$last_ajax_call = (isset($_REQUEST['timestamp'])) ? (int)$_REQUEST['timestamp'] : null;
			$last_change_data = time();

			if ($last_ajax_call == null || $last_change_data > $last_ajax_call) {

				$message_count = DB::numrows("
					SELECT message_id
					FROM " . DB::$db_prefix . "_message
					WHERE message_to = '".$_SESSION['user_id']."' AND message_open = '0'
				");

				// список пользователей
				$search_message = clear_text($_REQUEST['search_message']);
				$list_user_message = self::list_user_message($search_message);
				// список пользователей

			} else {
				// wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
				sleep(1);
				continue;
			}

			echo json_encode(array("message_count"=>$message_count, "list_user_message" => $list_user_message, 'timestamp' => $last_change_data));
			exit;
		}
	}

	static function message_reload() {
		$user_id = (int)$_REQUEST['message_to'];
		$message_last = (int)$_REQUEST['message_last'];

		set_time_limit(0);

		while (true) {
			$last_ajax_call = (isset($_REQUEST['timestamp'])) ? (int)$_REQUEST['timestamp'] : null;
			$last_change_data = time();

			if ($last_ajax_call == null || $last_change_data > $last_ajax_call) {

				$user_search = "
					AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."')
					AND (message_from = '".$user_id."' OR message_to = '".$user_id."')
				";

				if($_REQUEST['message_to'] == siteBot::$bot_id) {
					$user_search = "
						AND (message_from = '".siteBot::$bot_id."' AND message_to = '".$_SESSION['user_id']."')
					";
				}

				$count_all = DB::numrows("
					SELECT * FROM
						" . DB::$db_prefix . "_message as msg
					JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
					JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
					WHERE usr.user_status = '1' $user_search
				");

				$limit = self::$max_message_on_chat*2;
				$message_list = DB::fetchrow("
					SELECT * FROM
						" . DB::$db_prefix . "_message as msg
					JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
					JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
					WHERE usr.user_status = '1' $user_search
					ORDER BY message_date DESC
					LIMIT " . $limit
				);

				$message_list = array_reverse($message_list, true);

				foreach ($message_list as $row) {
					if($row->message_parent) {
						$row->message_parent = DB::row("
							SELECT * FROM
								" . DB::$db_prefix . "_message as msg
							JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
							JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
							WHERE message_id = '".$row->message_parent."'
						");
						$row->message_parent = self::get_info_about_message($row->message_parent);
					}

					$row = self::get_info_about_message($row);
				}

				// отметка о прочтении
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_message
					SET message_open = '1'
					WHERE message_from = '".$user_id."'
				");

			} else {
				// wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
				sleep(1);
				continue;
			}

			echo json_encode(array("count_all"=>$count_all, 'message_list' => $message_list, 'timestamp' => $last_change_data));
			exit;
		}

	}

	static function message_more() {
		$user_id = (int)$_REQUEST['message_to'];
		$message_first = (int)$_REQUEST['message_first'];

		$count_all = DB::numrows("
			SELECT * FROM
				" . DB::$db_prefix . "_message as msg
			JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
			JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
			WHERE usr.user_status = '1'
				AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."')
				AND (message_from = '".$user_id."' OR message_to = '".$user_id."')
		");

		$limit = self::$max_message_on_chat*2;
		$message_list = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_message as msg
			JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
			JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
			WHERE usr.user_status = '1'
				AND (message_from = '".$_SESSION['user_id']."' OR message_to = '".$_SESSION['user_id']."')
				AND (message_from = '".$user_id."' OR message_to = '".$user_id."') AND message_id < '".$message_first."'
			ORDER BY message_date DESC
			LIMIT $limit
		");

		$message_list = array_reverse($message_list, true);

		foreach ($message_list as $row) {

			if($row->message_parent) {
				$row->message_parent = DB::row("
					SELECT * FROM
						" . DB::$db_prefix . "_message as msg
					JOIN " . DB::$db_prefix . "_users as usr on msg.message_from = usr.id
					JOIN " . DB::$db_prefix . "_users as usr1 on msg.message_to = usr1.id
					WHERE message_id = '".$row->message_parent."'
				");
				$row->message_parent = self::get_info_about_message($row->message_parent);
			}

			$row = self::get_info_about_message($row);
		}

		// отметка о прочтении
		DB::query("
			UPDATE
				" . DB::$db_prefix . "_message
			SET message_open = '1'
			WHERE message_from = '".$user_id."'
		");

		echo json_encode(array("count_all"=>$count_all, 'message_list' => $message_list));
		exit;
	}

	static function get_info_about_message($message_info) {
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		
		if($message_info->message_document) {
			$message_info->message_document = siteSearch::get_info_about_document($message_info->message_document);

			$message_info->message_document_accept = DB::row("
				SELECT * FROM
					" . DB::$db_prefix . "_users_writer_accept
				WHERE accept_document_id = '".$message_info->message_document->document_id."'
			");
			if($message_info->message_document_accept) {
				$message_info->message_document_accept->accept_currency = $currency_list[$message_info->message_document_accept->accept_currency];
			}
		}

		if($message_info->message_attach) {
			$message_info->message_attach = unserialize($message_info->message_attach);
		}

		$message_info->message_date = date("d F Y h:m", $message_info->message_date);;
		$message_info->message_from = get_user_info($message_info->message_from);
		$message_info->message_to = get_user_info($message_info->message_to);

		twig::assign('message', $message_info);
		if($message_info->message_from->user_group != 5) {
			// проверка по типам
			switch ($message_info->message_type) {
				case '12': // отклики по резюме

					$resume_info = DB::row("
						SELECT * FROM
							" . DB::$db_prefix . "_users_resume
						WHERE resume_owner = '".$message_info->message_resume."'
					");
					$jobs_title = get_book_for_essence(21);
					$resume_info->resume_title = $jobs_title[$resume_info->resume_title];
					
					if($message_info->message_resume != $_SESSION['user_id'] && $message_info->message_from->id == $_SESSION['user_id']) {
						$message_info->message_str_block = twig::$lang["resume_respons_message_to"];
						$message_info->message_str_block = str_replace("#TITLE#", "<a href='".HOST_NAME."/resume-".$message_info->message_resume."/' class='upper'>".$resume_info->resume_title->title."</a>", $message_info->message_str_block);
						$message_info->message_str_block = str_replace("#OWNER#", "<a href='".HOST_NAME."/profile-".$message_info->message_resume."/'>".$resume_info->resume_lastname . " " . $resume_info->resume_firstname."</a>", $message_info->message_str_block);
					} else {
						$message_info->message_str_block = twig::$lang["resume_respons_message_from"];
						$message_info->message_str_block = str_replace("#TITLE#", "<a href='".HOST_NAME."/resume-".$message_info->message_resume."/' class='upper'>".$resume_info->resume_title->title."</a>", $message_info->message_str_block);
						$message_info->message_str_block = str_replace("#OWNER#", "<a href='".HOST_NAME."/profile-".$message_info->message_to->id."/'>".$message_info->message_to->user_name."</a>", $message_info->message_str_block);
					}

					$message_info->message_html_block = twig::fetch('frontend/message/message_respons_resume.tpl');
				break;

				case '13': // отклики по вакансиям

					$jobs_info = DB::row("
						SELECT * FROM
							" . DB::$db_prefix . "_users_jobs
						WHERE jobs_id = '".$message_info->message_jobs."'
					");
					$jobs_title = get_book_for_essence(21);
					$currency_list = get_book_for_essence(5);
					$jobs_info->jobs_title = $jobs_title[$jobs_info->jobs_title];
					$jobs_info->jobs_coast_currency = $currency_list[$jobs_info->jobs_coast_currency];
					$jobs_info->jobs_owner = get_user_info($jobs_info->jobs_owner);
					
					if($jobs_info->jobs_owner->id != $_SESSION['user_id'] && $message_info->message_from->id == $_SESSION['user_id']) {
						$message_info->message_str_block = twig::$lang["jobs_respons_message_to"];
						$message_info->message_str_block = str_replace("#TITLE#", "<a href='".HOST_NAME."/jobs-".$message_info->message_jobs."/' class='upper'>".$jobs_info->jobs_title->title."</a>", $message_info->message_str_block);
						$message_info->message_str_block = str_replace("#OWNER#", "<a href='".HOST_NAME."/profile-".$jobs_info->jobs_owner->id."/'>".$jobs_info->jobs_owner->user_name ."</a>", $message_info->message_str_block);
					} else {
						$message_info->message_str_block = twig::$lang["jobs_respons_message_from"];
						$message_info->message_str_block = str_replace("#TITLE#", "<a href='".HOST_NAME."/jobs-".$message_info->message_jobs."/' class='upper'>".$jobs_info->jobs_title->title."</a>", $message_info->message_str_block);
						$message_info->message_str_block = str_replace("#OWNER#", "<a href='".HOST_NAME."/profile-".$message_info->message_to->id."/'>".$message_info->message_to->user_name."</a>", $message_info->message_str_block);
					}

					twig::assign('jobs_info', $jobs_info);

					$message_info->message_html_block = twig::fetch('frontend/message/message_respons_jobs.tpl');
				break;

				default:
					$message_info->message_html_block = twig::fetch('frontend/chank/message_list_simple.tpl');
				break;
			}
			// проверка по типам
			
		} else { // свой шаблон для сообщений от бота

			$message_info->message_html_block = "";

			// проверка по типам
			switch ($message_info->message_type) {
				case '1':
					$message_info->message_html = twig::fetch('frontend/message/velcom.tpl');
				break;

				case '14':

					$message_info->message_html = twig::fetch('frontend/message/document_invite.tpl');
					$message_info->message_html = str_replace("#document_id#", $message_info->message_document->document_id, $message_info->message_html);

				break;
			}
			// проверка по типам

			if($message_info->message_respons) {

				$respons_info = DB::row("
					SELECT * FROM
						" . DB::$db_prefix . "_users_response
					WHERE response_id = '".$message_info->message_respons."'
				");

				if($respons_info) {
					twig::assign('respons_info', $respons_info);

					if($respons_info->response_work) {
						$order_info = siteOrder::get_info_about_order($respons_info->response_work);
					}

					if($respons_info->response_jobs) {
						$jobs_info = siteJobs::get_info_about_jobs($respons_info->response_jobs);
					}

					if($respons_info->response_owner) {
						$resume_info = siteResume::get_info_about_resume($respons_info->response_owner);
					}

					$country_by_lang = country_by_lang();
					$users = get_work_user();

					switch ($message_info->message_type) {
						case '2':
							$bot_message = twig::$lang["bot_order_2"];
							$bot_message = str_replace("#order_id#", $respons_info->response_work, $bot_message);
							$bot_message = str_replace("#order_skill#", $order_info->order_service->title, $bot_message);
							$bot_message = str_replace("#order_lang_from#", $order_info->order_lang_from->title, $bot_message);
							$bot_message = str_replace("#order_lang_to#", $order_info->order_lang_to->title, $bot_message);
							$bot_message = str_replace("#order_start#", $order_info->order_start, $bot_message);
							$bot_message = str_replace("#order_end#", $order_info->order_end, $bot_message);
							$bot_message = str_replace("#order_city#", $order_info->order_city['title'], $bot_message);
							$bot_message = str_replace("#order_country#", $country_by_lang[$order_info->order_country]['title'], $bot_message);
							$bot_message = str_replace("#order_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_perfomens_accept.tpl');
						break;

						case '3':
							$bot_message = twig::$lang["bot_order_3"];
							$bot_message = str_replace("#order_id#", $respons_info->response_work, $bot_message);
							$bot_message = str_replace("#order_skill#", $order_info->order_service->title, $bot_message);
							$bot_message = str_replace("#order_lang_from#", $order_info->order_lang_from->title, $bot_message);
							$bot_message = str_replace("#order_lang_to#", $order_info->order_lang_to->title, $bot_message);
							$bot_message = str_replace("#order_start#", $order_info->order_start, $bot_message);
							$bot_message = str_replace("#order_end#", $order_info->order_end, $bot_message);
							$bot_message = str_replace("#order_city#", $order_info->order_city['title'], $bot_message);
							$bot_message = str_replace("#order_country#", $country_by_lang[$order_info->order_country]['title'], $bot_message);
							$bot_message = str_replace("#order_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_owner_perfomens_accept.tpl');
						break;

						case '4':

							$bot_message = twig::$lang["bot_order_4"];
							$bot_message = str_replace("#order_id#", $respons_info->response_work, $bot_message);
							$bot_message = str_replace("#order_skill#", $order_info->order_service->title, $bot_message);
							$bot_message = str_replace("#order_lang_from#", $order_info->order_lang_from->title, $bot_message);
							$bot_message = str_replace("#order_lang_to#", $order_info->order_lang_to->title, $bot_message);
							$bot_message = str_replace("#order_start#", $order_info->order_start, $bot_message);
							$bot_message = str_replace("#order_end#", $order_info->order_end, $bot_message);
							$bot_message = str_replace("#order_city#", $order_info->order_city['title'], $bot_message);
							$bot_message = str_replace("#order_country#", $country_by_lang[$order_info->order_country]['title'], $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_owner_change_perfomens.tpl');
						break;

						case '5':
							$bot_message = twig::$lang["bot_order_5"];
							$bot_message = str_replace("#order_id#", $respons_info->response_work, $bot_message);
							$bot_message = str_replace("#order_skill#", $order_info->order_service->title, $bot_message);
							$bot_message = str_replace("#order_lang_from#", $order_info->order_lang_from->title, $bot_message);
							$bot_message = str_replace("#order_lang_to#", $order_info->order_lang_to->title, $bot_message);
							$bot_message = str_replace("#order_start#", $order_info->order_start, $bot_message);
							$bot_message = str_replace("#order_end#", $order_info->order_end, $bot_message);
							$bot_message = str_replace("#order_city#", $order_info->order_city['title'], $bot_message);
							$bot_message = str_replace("#order_country#", $country_by_lang[$order_info->order_country]['title'], $bot_message);
							$bot_message = str_replace("#order_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_perfomens_owner_accept.tpl');
						break;

						case '6':
							$bot_message = twig::$lang["bot_order_6"];
							$bot_message = str_replace("#jobs_id#", $jobs_info->jobs_id, $bot_message);
							$bot_message = str_replace("#jobs_city#", $jobs_info->jobs_city['title'], $bot_message);
							$bot_message = str_replace("#jobs_country#", $country_by_lang[$jobs_info->jobs_country]['title'], $bot_message);
							$bot_message = str_replace("#jobs_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/jobs_perfomens_access.tpl');
						break;

						case '7':
							$bot_message = twig::$lang["bot_order_7"];
							$bot_message = str_replace("#jobs_id#", $jobs_info->jobs_id, $bot_message);
							$bot_message = str_replace("#jobs_city#", $jobs_info->jobs_city['title'], $bot_message);
							$bot_message = str_replace("#jobs_country#", $country_by_lang[$jobs_info->jobs_country]['title'], $bot_message);
							$bot_message = str_replace("#jobs_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/jobs_owner_perfomens_accept.tpl');
						break;

						case '8':
							$bot_message = twig::$lang["bot_order_8"];
							$bot_message = str_replace("#resume_id#", $resume_info->resume_id, $bot_message);
							$bot_message = str_replace("#resume_owner#", $users[$respons_info->response_owner]->user_name . " " . $respons_info->response_owner, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/resume_owner_access.tpl');
						break;

						case '9':
							$bot_message = twig::$lang["bot_order_9"];
							$bot_message = str_replace("#resume_id#", $resume_info->resume_id, $bot_message);
							$bot_message = str_replace("#resume_owner#", $users[$respons_info->response_owner]->user_name . " " . $respons_info->response_owner, $bot_message);

							$order_info->bot_message = $bot_message;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/resume_owner_perfomens_accept.tpl');
						break;

						case '10':
							$bot_message = twig::$lang["bot_order_10"];
							$bot_message = str_replace("#order_id#", $order_info->order_id, $bot_message);
							$bot_message = str_replace("#order_owner#", $users[$order_info->order_owner]->user_name . " " . $order_info->order_owner, $bot_message);

							if($order_info->order_perfomens_star) {
								$order_info->otziv = DB::row("
									SELECT * FROM
										" . DB::$db_prefix . "_users_otziv
									WHERE otzive_order = '".$order_info->order_id."' AND otziv_to_id = '".$_SESSION['user_id']."'
								");
							}

							$order_info->bot_message = $bot_message;
							$order_info->bot_id = siteBot::$bot_id;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_owner_perfomens_close.tpl');
						break;

						case '11':
							$bot_message = twig::$lang["bot_order_11"];
							$bot_message = str_replace("#order_id#", $order_info->order_id, $bot_message);
							$bot_message = str_replace("#order_perfomens#", $users[$respons_info->response_perfomens]->user_name . " " . $respons_info->response_perfomens, $bot_message);

							if($order_info->order_owner_star) {
								$order_info->otziv = DB::row("
									SELECT * FROM
										" . DB::$db_prefix . "_users_otziv
									WHERE otzive_order = '".$order_info->order_id."' AND otziv_to_id = '".$_SESSION['user_id']."'
								");
							}

							$order_info->bot_message = $bot_message;
							$order_info->bot_id = siteBot::$bot_id;
							twig::assign('order_info', $order_info);

							$message_info->message_html = twig::fetch('frontend/message/order_owner_perfomens_close.tpl');
						break;
					}
				}

				twig::assign('message', $message_info);

			}

			$message_info->message_html_block = twig::fetch('frontend/chank/message_list_bot.tpl');
		}
		return $message_info;
	}



	static function send_otziv() {
		$error = [];

		//$user_id = (int)$_REQUEST['otziv_to_id'];
		$order_id = (int)$_REQUEST['order_id'];
		$message = clear_text($_REQUEST['otziv_text']);
		$otziv_star = (int)$_REQUEST['otziv_star'];

		try {
			v::length(config::app('app_min_strlen'), null)->assert($message);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["message_desc_error"];
		}

		$profile_info = get_user_info($user_id);
		if(!$profile_info || $user_id == $_SESSION['user_id']) {
			$error[] = twig::$lang["message_to_error"];
		}

		if($otziv_star > 5 || $otziv_star < 1) {
			$error[] = twig::$lang["message_star_error"];
		}

		$order_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_order
			WHERE order_id = '".$order_id."' AND order_status = '1' AND order_close = '1' AND order_perfomens != '0' AND order_delete != '1' AND (order_owner = '".$_SESSION['user_id']."' OR order_perfomens = '".$_SESSION['user_id']."')
		");
		if(!$order_info) {
			$error[] = twig::$lang["message_order_error"];
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

		/*if($_SESSION['user_group'] == 4) {
			$message_desc = ($_REQUEST['otziv_type'] == 1) ? twig::$lang["message_order_owner_accept"] : twig::$lang["message_order_owner_deny"];

			if($_REQUEST['otziv_type'] == 1) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_accept = '1'
					WHERE order_owner = '".$_SESSION['user_id']."' AND order_id = '".$order_id."'
				");
			}
		}*/

		$user_id = ($_SESSION['user_group'] == 4) ? $order_info->order_perfomens : $user_id = $order_info->order_owner;

		$check = DB::row("
			SELECT otziv_id FROM
				" . DB::$db_prefix . "_users_otziv
			WHERE
				otziv_to_id = '".$user_id."' AND otziv_owner = '".$_SESSION['user_id']."' AND otzive_order = '".$order_id."'
		");

		if(!$check) {

			if($_SESSION['user_group'] == 4) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_owner_star = '".$otziv_star."',
						order_accept = '1'
					WHERE order_id = '".$order_id."'
				");
			} else {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_order
					SET
						order_perfomens_star = '".$otziv_star."'
					WHERE order_id = '".$order_id."'
				");
			}

			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_otziv
				SET
					otziv_to_id = '".$user_id."',
					otziv_owner = '".$_SESSION['user_id']."' ,
					otzive_order = '".$order_id."',
					otziv_date = '".time()."',
					otziv_text = '".$message."',
					otziv_star = '".$otziv_star."'
			");

			/*$message = $message_desc . " " . $message;
			self::message_add($_SESSION['user_id'], $user_id, $message);*/
		}

		header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		exit;
	}

	static function send_accept_perfomens() {
		$error = [];

		if($_SESSION['user_group'] != 2) {
			site::error404();
		}

		$document_perfomens = (int)$_REQUEST['document_perfomens'];
		$message_to = (int)$_REQUEST['message_to'];
		$message_id = (int)$_REQUEST['message_id'];
		$document_id = (int)$_REQUEST['document_id'];
		$coast = (int)$_REQUEST['coast'];
		$currency = (int)$_REQUEST['currency'];
		$date = clear_text($_REQUEST['date']);
		$get = clear_text($_REQUEST['get']);
		$message = clear_text($_REQUEST['comment']);

		try {
			v::length(config::app('app_min_strlen'), null)->assert($message);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["message_desc_error"];
		}

		$document_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_writer
			WHERE document_id = '".$document_id."'
		");
		if(!$document_info) {
			$error[] = twig::$lang["message_order_error"];
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
				" . DB::$db_prefix . "_users_writer_accept
			SET
				accept_document_id = '".$document_id."',
				accept_coast = '".$coast."',
				accept_currency = '".$currency."',
				accept_date = '".$date."',
				accept_get = '".$get."',
				accept_comment = '".$message."'
		");

		$currency_list = get_book_for_essence(5);
		twig::assign('currency', $currency_list[$currency]);
		twig::assign('coast', $coast);
		twig::assign('date', $date);
		twig::assign('get', $get);
		twig::assign('message', $message);
		twig::assign('document_info', $document_info);
		$message = twig::fetch('message_accept_perfomens.tpl');

		self::message_add($_SESSION['user_id'], $message_to, $message, ["document_id"=>$document_id, "document_perfomens"=>$document_perfomens]);

		header('Location:'.HOST_NAME.'/message/?message_to='.$message_to);
		exit;
	}

	static function accept_perfomens() {
		$error = [];

		if($_SESSION['user_group'] != 4) {
			site::error404();
		}

		$document_perfomens = (int)$_REQUEST['document_perfomens'];
		$accept_type = (int)$_REQUEST['accept_type'];
		$document_id = (int)$_REQUEST['message_document'];
		$message_to = (int)$_REQUEST['message_to'];
		$message_id = (int)$_REQUEST['message_id'];
		$comment = clear_text($_REQUEST['comment']);

		try {
			v::length(config::app('app_min_strlen'), null)->assert($comment);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["message_desc_error"];
		}

		// добавить првоерку на пользователя !!!!!!!!!! ++++

		$document_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_writer
			WHERE document_id = '".$document_id."' AND document_owner = '".$_SESSION['user_id']."'
		");
		if(!$document_info) {
			$error[] = twig::$lang["message_order_error"];
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

		switch ($accept_type) {
			case '1':
			break;

			case '2':
				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_writer_accept
					WHERE
						accept_document_id = '".$document_id."'
				");
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_message
					SET
						message_document_perfomens = '0'
					WHERE
						message_document = '".$document_id."'
				");
				$message = twig::$lang["message_document_accept_deny"];
			break;

			case '3':
				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_writer_accept
					WHERE
						accept_document_id = '".$document_id."'
				");
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_message
					SET
						message_document_perfomens = '0'
					WHERE
						message_document = '".$document_id."'
				");
				$message = twig::$lang["message_document_accept_other"];
			break;
		}

		self::message_add($_SESSION['user_id'], $message_to, $message . " " . $comment);

		header('Location:'.HOST_NAME.'/message/?message_to='.$message_to);
		exit;
	}

	static function writer_search_send() {
		if($_SESSION['user_group'] == 2) {
			$send_message = $_REQUEST['send_message'];
			$writer_comment = clear_text($_REQUEST['writer_comment']);
			$document_id = (int)$_REQUEST['document_id'];

			$document_info = siteSearch::get_info_about_document($document_id);

			twig::assign('document_info', $document_info);
			twig::assign('writer_comment', $writer_comment);
			$message = twig::fetch('message_writer_add.tpl');

			foreach ($send_message as $user_id) {
				self::message_add($_SESSION['user_id'], $user_id, $message);
			}

			echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
			exit;
		}
		exit;
	}

	static function message_upload($message_id) {

		$attach = json_decode(stripslashes($_REQUEST['fileuploader-list-upload_file']));

		$message_to = (int)$_REQUEST['message_to'];
		$count = 0;
		// сохранение фотографии
		$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_message_dir')."/".$message_to."/"; // адрес директории с изображениями
		if(!is_dir($targetPath)) {
			@mkdir($targetPath);
			chmod($targetPath, 0755);
		}

		$have_file = [];
		$respons = [];

		foreach ($_FILES['upload_file']['name'] as $key => $value) {
			if($_FILES['upload_file']['name'][$key] && image_get_info($_FILES['upload_file']['tmp_name'][$key])) {

				$photos_tmp = $_FILES['upload_file']['tmp_name'][$key]; // изображения для данных
				$photos_name = $_FILES['upload_file']['name'][$key]; // изображения для данных
				if(image_get_info($photos_tmp)) {
					$photos = rename_file($photos_name, $targetPath); // переименовываем
				}

				$use_original_name = explode(".", $_FILES['upload_file']['name'][$key]);
				array_pop($use_original_name);
				$use_original_name = implode(".", $use_original_name);

				if(mb_strlen($use_original_name) < \config::app('app_min_strlen')) {
					$use_new_name = explode(".", $photos);
					$use_original_name = $use_new_name[0];
				}

				if ($photos != false) {
					$targetFile =  $targetPath . $photos;
					move_uploaded_file($photos_tmp, $targetFile);

					require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';
					$thumb = new PHPThumb\GD($targetFile);

					// поворот
					if($attach[$key]->editor->rotation) {
						$thumb->rotateImage($attach[$key]->editor->rotation);
					}
					// поворот

					// кроп
					if($attach[$key]->editor->crop) {
						$thumb->crop($attach[$key]->editor->crop->left, $attach[$key]->editor->crop->top, $attach[$key]->editor->crop->width, $attach[$key]->editor->crop->height);
					}
					// кроп

					$thumb->resize(600, 600);
					$thumb->save($targetFile);

					$have_file[] = $photos;

					if($_REQUEST['no_uploads'] == 1) {
						$respons = [
							"hasWarnings" => false,
							"isSuccess" => true,
							"warnings" => [],
							"files" => [
								[
									"date" => date(DATE_RFC822, time()),
									"editor" => false,
									"extension" => array_pop(explode(".", $_FILES['upload_file']['name'][$key])),
									"name" => clear_text($use_original_name).".".array_pop(explode(".", $_FILES['upload_file']['name'][$key])),
									"old_name" => clear_text($use_original_name).".".array_pop(explode(".", $_FILES['upload_file']['name'][$key])),
									"title" => clear_text($use_original_name),
									"old_title" => clear_text($use_original_name),
									"file" => ABS_PATH  . config::app('app_upload_dir')."/".config::app('app_message_dir')."/".$message_to."/" . $photos,
									"uploaded" => true,
									"replaced" => false,
									"size" => filesize($targetFile),
									"type" => mime_content_type($targetFile),
								]
							]
						];

						echo json_encode($respons);
	    				exit;
					}

				}
			}
		}

		if($have_file) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_message
				SET
					message_attach = '". serialize($have_file) ."'
				WHERE message_id = '".$message_id."'
			");
		}

		return true;
	}

	static function upload_file_form() {

		$app_allow_ext = \config::app('app_allow_img');
		\twig::assign('app_allow_ext_arr', $app_allow_ext);
		$app_allow_ext = implode(", ", $app_allow_ext);
		\twig::assign('app_allow_ext', $app_allow_ext);

		\twig::assign('send_message_to', $_REQUEST['void_id']);

		$html = twig::fetch('frontend/chank/message_form_uploads.tpl');
		echo json_encode(array("title"=>twig::$lang["message_uploads_title"],"html"=>$html, "status"=>"success"));
		exit;
	}

}

?>
