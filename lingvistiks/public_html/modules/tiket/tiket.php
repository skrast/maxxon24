<?php
namespace modules;

class tiket {
	static $mod_folder = "";

	function __construct() {

	}

	static function module_info($custom='') {
		$module['module_name'] = \twig::$lang["tiket_name"];
		$module['module_name_short'] = \twig::$lang["tiket_name_short"];
		$module['module_tag'] = "tiket";
		$module['module_version'] = "1.0";
		$module['module_desc'] = \twig::$lang["tiket_description"];
		$module['module_author'] = "Максим Медведев";
		$module['module_copy'] = "&copy; 2017 Реймакс";
		$module['module_settings'] = 1;
		$module['module_main_access'] = [1, 3];
		$module['module_main_answer'] = [1, 2];
		$module['module_allow_link'] = 1;

		self::$mod_folder = '/' . $module['module_tag'] . "/templates/";
		return ($custom!='') ? $module[$custom] : $module;
	}

	static function validation_permission() {
		$module_info = self::module_info();
		if(!$_SESSION['alles'] && !in_array($_SESSION['user_group'], $module_info['module_main_access'])) {
			return false;
		}
		return true;
	}

	static function check_permission() {
		if(self::validation_permission() === false) {
			access_404();
		}
	}

	static function bild_filter() {
		// поиск
		$page_link = (isset($_REQUEST['filter'])) ? "&filter=1" : "";
		$sql_link = "";

		$tiket_group_list = self::get_tiket_group();
		\twig::assign('tiket_group_list', $tiket_group_list);

		\twig::assign('tiket_status_array', \twig::$lang["tiket_status_array"]);

		if(!empty($_REQUEST['start'])) {
			$start_time = date_to_unix(urldecode($_REQUEST['start']));
			\twig::assign('start', date('d.m.Y H:i', $start_time));
		}
		if(!empty($_REQUEST['end'])) {
			$end_time = date_to_unix(urldecode($_REQUEST['end']));
			\twig::assign('end', date('d.m.Y H:i', $end_time));
		}

		if(!empty($start_time)) {
			$sql_link .= "AND tiket_add >= '".$start_time."' ";
			$page_link .= "&start=".urlencode(date('d.m.Y H:i', $start_time));
		}
		if(!empty($end_time)) {
			$sql_link .= "AND tiket_add <= '".$end_time."' ";
			$page_link .= "&end=".urlencode(date('d.m.Y H:i', $end_time));
		}

		if(!empty($_REQUEST['tiket_title'])) {
			$sql_link .= " AND tiket_title LIKE '%".clear_text($_REQUEST['tiket_title'])."%' ";
			$page_link .= "&tiket_title=".urlencode(clear_text($_REQUEST['tiket_title']));
		}

		if(!empty($_REQUEST['tiket_id'])) {
			$sql_link .= " AND tiket_id = '".(int)$_REQUEST['tiket_id']."' ";
			$page_link .= "&tiket_id=".(int)$_REQUEST['tiket_id'];
		}

		if(!empty($_REQUEST['tiket_status'])) {
			$search_status = [];
			foreach ($_REQUEST['tiket_status'] as $value) {
				$search_status[] = (int)$value;
				$page_link .= "&tiket_status[]=".(int)$value;
			}
			if($search_status) {
				$sql_link .= " AND tiket_close IN (".implode(",", $search_status).") ";
			}

		}

		if($tiket_group_list && $_REQUEST['tiket_group'] && is_array($_REQUEST['tiket_group'])) {
			$search_group = [];
			foreach ($_REQUEST['tiket_group'] as $value) {
				if(array_key_exists($value, $tiket_group_list)) {
					$search_group[] = (int)$value;
					$page_link .= "&tiket_group[]=".(int)$value;
				}
			}
			if($search_group) {
				$sql_link .= " AND tiket_group IN (".implode(",", $search_group).") ";
			}
		}

		if(!empty($_REQUEST['tiket_tags'])) {
			$tiket_tags = explode(",", $_REQUEST['tiket_tags']);
			$search_tags = [];
			foreach ($tiket_tags as $value) {
				$search_tags[] = "tiket_tags LIKE '%".clear_text($value)."%'";
			}
			if($search_tags) {
				$sql_link .= " AND (".implode(" OR ", $search_tags).") ";
				$page_link .= "&tiket_tags=".urlencode(clear_text($_REQUEST['tiket_tags']));
			}
		}

		return array($page_link, $sql_link);
	}

	static function tiket_start() {
		$module_info = self::module_info();

		// сборка параметров для поиска
		$bild_filter = self::bild_filter();
		$page_link = $bild_filter[0];
		$sql_link = $bild_filter[1];
		// сборка параметров для поиска

		// pager
		$start = get_current_page() * \config::app('SYS_PEAR_PAGE') - \config::app('SYS_PEAR_PAGE');
		// pager

		$sql_link .= ($_SESSION['alles'] || in_array($_SESSION['user_group'], $module_info['module_main_access'])) ? " " : " AND tiket_owner = '".$_SESSION['user_group']."' ";

		$tiket_list = \DB::fetchrow("
			SELECT
				*,
				(SELECT COUNT(comment_id) FROM " . \DB::$db_prefix . "_module_tiket_comment as cmn WHERE cmn.tiket_id = tik.tiket_id) as tiket_comment_count
			FROM " . \DB::$db_prefix . "_module_tiket as tik
			WHERE 1=1 $sql_link
			ORDER BY tiket_id DESC
			LIMIT " . $start . "," . \config::app('SYS_PEAR_PAGE') . "
		");
		foreach ($tiket_list as $key => $value) {
			$value = self::tiket_info($value);
		}
		\twig::assign('tiket_list', $tiket_list);

		// pager
		$num = \DB::numrows("
			SELECT
				tiket_id
			FROM " . \DB::$db_prefix . "_module_tiket
			WHERE 1=1 $sql_link
		");
		\twig::assign('num', $num);
		if ($num > \config::app('SYS_PEAR_PAGE'))
		{
			$page_nav = get_pagination(ceil($num / \config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=module&sub=mod_edit&module_tag=tiket&module_action=faq_start'.$page_link.'&page={s}">{t}</a>', \config::app('SYS_PEAR_NAVI_BOX'));
			\twig::assign('page_nav', $page_nav);
		}
		// pager

		/* пользователи */
		$main_users = get_work_user();
		\twig::assign('main_users', $main_users);
		/* пользователи */

		\twig::assign('module_info', $module_info);
		\twig::assign('content', \twig::fetch(self::$mod_folder . 'start.tpl'));
	}

	static function tiket_open() {
		$module_info = self::module_info();
		$main_users = get_work_user();

		$tiket_group_list = self::get_tiket_group();
		$tiket_id = (int)$_REQUEST['tiket_id'];

		$tiket_info = self::get_info_about_tiket($tiket_id);
		if(!$tiket_info || (!$_SESSION['alles'] && !in_array($_SESSION['user_group'], $module_info['module_main_access']) && $tiket_info->tiket_owner->id != $_SESSION['user_id'])) {
			access_404();
		}

		if(!$tiket_info->tiket_answer && in_array($_SESSION['user_group'], $module_info['module_main_answer']) && $_SESSION['user_id'] != $tiket_info->tiket_owner->id) {
			\DB::query("
				UPDATE " . \DB::$db_prefix . "_module_tiket
				SET tiket_answer = '".$_SESSION['user_id']."'
				WHERE tiket_id = '".$tiket_info->tiket_id."'
			");
			$tiket_info->tiket_answer = $main_users[$_SESSION['user_id']];
		}

		if($tiket_info->tiket_owner_open!=1 && $tiket_info->tiket_owner->id == $_SESSION['user_id']) {
			\DB::query("
				UPDATE " . \DB::$db_prefix . "_module_tiket
				SET tiket_owner_open = '1'
				WHERE tiket_id = '".$tiket_info->tiket_id."'
			");
			$tiket_info->tiket_owner_open = 1;
		}

		if($tiket_info->tiket_answer_open!=1 && $tiket_info->tiket_answer->id == $_SESSION['user_id']) {
			\DB::query("
				UPDATE " . \DB::$db_prefix . "_module_tiket
				SET tiket_answer_open = '1'
				WHERE tiket_id = '".$tiket_info->tiket_id."'
			");
			$tiket_info->tiket_answer_open = 1;
		}

		\twig::assign('tiket_info', $tiket_info);

		// комментарии
		$comment_list = \DB::fetchrow("
			SELECT
				*
			FROM " . \DB::$db_prefix . "_module_tiket_comment
			WHERE tiket_id = '".$tiket_info->tiket_id."'
			ORDER BY comment_id ASC
		");
		foreach ($comment_list as $key => $value) {
			$value = self::comment_info($value);
		}
		\twig::assign('comment_list', $comment_list);
		// комментарии

		\twig::assign('module_info', self::module_info());
		\twig::assign('content', \twig::fetch(self::$mod_folder . 'open.tpl'));
	}

	static function tiket_work() {

		if(isset($_REQUEST['delete_tiket_id']) && $_SESSION['alles']) {
			$tiket_id = (int)$_REQUEST['delete_tiket_id'];
			$tiket_info = self::get_info_about_tiket($tiket_id);
			if(!$tiket_info) {
				access_404();
			}

			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_tiket
				WHERE tiket_id = '".$tiket_id."'
			");

			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_tiket_comment
				WHERE tiket_id = '".$tiket_id."'
			");

			\logs::add(\twig::$lang["tiket_log_delete"].' (' . $tiket_id . ')', \module::$log_setting);
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start');
			exit;
		}

		if(isset($_REQUEST['close_tiket'])) {
			$tiket_id = (int)$_REQUEST['close_tiket'];
			$tiket_info = self::get_info_about_tiket($_REQUEST['close_tiket']);
			if(!$tiket_info || $tiket_info->tiket_close == 1) {
				access_404();
			}

			\DB::query("
				UPDATE
					" . \DB::$db_prefix . "_module_tiket
				SET
					tiket_close = '1',
					tiket_edit = '".time()."'
				WHERE
					tiket_id = '".$tiket_id."'
			");
			\logs::add(\twig::$lang["tiket_log_close"].' (' . $tiket_id . ')', \module::$log_setting);

			$hist[] = \twig::$lang["tiket_log_close"];
			\twig::assign('text_array', $hist);
			$text_prep = \twig::fetch('chank/history_boot.tpl');

			\DB::query("
				INSERT INTO
					" . \DB::$db_prefix . "_module_tiket_comment
				SET
					tiket_id = '".$tiket_id."',
					comment_text = '".$text_prep."',
					comment_date = '".time()."',
					comment_owner = '".$_SESSION['user_id']."'
			");

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id='.$tiket_id);
			exit;
		}

		$module_info = self::module_info();
		$tiket_group_list = self::get_tiket_group();
		\twig::assign('tiket_group_list', $tiket_group_list);

		if(!empty($_REQUEST['tiket_id'])) {
			$tiket_id = (int)$_REQUEST['tiket_id'];
			$tiket_info = self::get_info_about_tiket($tiket_id);
			if(!$tiket_info || (!$_SESSION['alles'] && !in_array($_SESSION['user_group'], $module_info['module_main_access']) && $tiket_info->tiket_owner->id != $_SESSION['user_id']) || $tiket_info->tiket_close == 1) {
				access_404();
			}
			\twig::assign('tiket_info', $tiket_info);
		}

		if(isset($_REQUEST['save'])) {
			$error = [];
			$tiket_title = strip_tags(addslashes($_REQUEST['tiket_title']));
			$tiket_tags = strip_tags(addslashes($_REQUEST['tiket_tags']));
			$tiket_text = clear_tags($_REQUEST['tiket_text']);
			$tiket_group = (int)$_REQUEST['tiket_group'];

			if(!array_key_exists($tiket_group, $tiket_group_list)) {
				$error[] = \twig::$lang["tiket_group_error"];
			}

			if(mb_strlen($tiket_title)<\config::app('app_min_strlen')) {
				$error[] = \twig::$lang["tiket_title_error"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			$tiket_tags = explode(",", $tiket_tags);
			$tiket_tags = array_map('trim',$tiket_tags);
			$tiket_tags = array_unique($tiket_tags);
			$tiket_tags = implode(",", $tiket_tags);

			if(!empty($_REQUEST['tiket_id'])) {

				$hist = [];
				if(
					$tiket_info->tiket_title != $_REQUEST['tiket_title']
				) {
					$new = $_REQUEST['tiket_title'];
					$old = $tiket_info->tiket_title;

					$hist[] = \twig::$lang["tiket_title"] ." ".$old." - ".$new;
				}

				if(
					$tiket_info->tiket_tags != $tiket_tags
				) {
					$new = $tiket_tags ? $tiket_tags : \twig::$lang["tiket_log_none"];
					$old = $tiket_info->tiket_tags ? $tiket_info->tiket_tags : \twig::$lang["tiket_log_none"];

					$hist[] = \twig::$lang["tiket_tags"] ." ".$old." - ".$new;
				}

				if(
					$tiket_info->tiket_group->tiket_group_id != $_REQUEST['tiket_group']
				) {
					$new = $tiket_group_list[$_REQUEST['tiket_group']]->tiket_group_title;
					$old = $tiket_group_list[$tiket_info->tiket_group->tiket_group_id]->tiket_group_title;

					$hist[] = \twig::$lang["tiket_group"] ." ".$old." - ".$new;
				}

				if(
					$tiket_info->tiket_text != $tiket_text
				) {
					$new = $tiket_text;
					$old = $tiket_info->tiket_text;

					$hist[] = \twig::$lang["tiket_text"] ." ".$old." - ".$new;
				}

				if($hist) {
					\twig::assign('text_array', $hist);
					$text_prep = \twig::fetch('chank/history_boot.tpl');

					\DB::query("
						INSERT INTO
							" . \DB::$db_prefix . "_module_tiket_comment
						SET
							tiket_id = '".$tiket_id."',
							comment_text = '".$text_prep."',
							comment_date = '".time()."',
							comment_owner = '".$_SESSION['user_id']."'
					");
				}

				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_tiket
					SET
						tiket_title = '".$tiket_title."',
						tiket_tags = '".$tiket_tags."',
						tiket_text = '".$tiket_text."',
						tiket_group = '".$tiket_group."',
						tiket_edit = '".time()."'
					WHERE
						tiket_id = '".$tiket_id."'
				");
				\logs::add(\twig::$lang["tiket_log_edit"].' (' . $tiket_id . ')', \module::$log_setting);

				echo json_encode(array("respons"=>\twig::$lang["form_save_success"], "status"=>"success"));
			} else {
				\DB::query("
					INSERT INTO
						" . \DB::$db_prefix . "_module_tiket
					SET
						tiket_title = '".$tiket_title."',
						tiket_tags = '".$tiket_tags."',
						tiket_text = '".$tiket_text."',
						tiket_group = '".$tiket_group."',
						tiket_add = '".time()."',
						tiket_owner = '".$_SESSION['user_id']."'
				");
				$tiket_id = \DB::lastInsertId();
				\logs::add(\twig::$lang["tiket_log_add"].' (' . $tiket_id . ')', \module::$log_setting);

				echo json_encode(array("ref"=>ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&tiket_id='.$tiket_id, "status"=>"success"));
			}
			exit;
		}

		\twig::assign('module_info', self::module_info());
		\twig::assign('content', \twig::fetch(self::$mod_folder . 'work.tpl'));
	}

	static function tiket_comment_work() {
		$main_users = get_work_user();

		$tiket_id = (int)$_REQUEST['tiket_id'];
		$tiket_info = self::get_info_about_tiket($tiket_id);
		if(!$tiket_info || (!$_SESSION['alles'] && !in_array($_SESSION['user_group'], $module_info['module_main_access']) && $tiket_info->tiket_owner->id != $_SESSION['user_id'])) {
			access_404();
		}

		if(isset($_REQUEST['save'])) {
			$error = [];
			$comment_text = clear_tags($_REQUEST['comment_text']);

			if(mb_strlen($comment_text)<\config::app('app_min_strlen')) {
				$error[] = \twig::$lang["tiket_comment_text_error"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			\DB::query("
				INSERT INTO
					" . \DB::$db_prefix . "_module_tiket_comment
				SET
					tiket_id = '".$tiket_id."',
					comment_text = '".$comment_text."',
					comment_date = '".time()."',
					comment_owner = '".$_SESSION['user_id']."'
			");
			$comment_id = \DB::lastInsertId();

			/*if($tiket_info->tiket_answer_open==1 && $tiket_info->tiket_owner->id == $_SESSION['user_id']) {
				\DB::query("
					UPDATE " . \DB::$db_prefix . "_module_tiket
					SET tiket_answer_open = '0'
					WHERE tiket_id = '".$tiket_info->tiket_id."'
				");

				$mail_body = \twig::$lang["tiket_mail_text"];
				$mail_body = str_ireplace("#ID#", $tiket_info->tiket_id, $mail_body);
				$mail_body = str_ireplace("#LINK#", HOST.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id='.$tiket_info->tiket_id, $mail_body);
				$mail_title = \twig::$lang["tiket_mail_title"];
				$user_info = $main_users[$tiket_info->tiket_owner->id];

				sendmail::sendmail_bilder($user_info, $mail_title, $mail_body);
			}

			if($tiket_info->tiket_owner_open==1 && $tiket_info->tiket_answer->id == $_SESSION['user_id']) {
				\DB::query("
					UPDATE " . \DB::$db_prefix . "_module_tiket
					SET tiket_owner_open = '0'
					WHERE tiket_id = '".$tiket_info->tiket_id."'
				");

				$mail_body = \twig::$lang["tiket_mail_text"];
				$mail_body = str_ireplace("#ID#", $tiket_info->tiket_id, $mail_body);
				$mail_body = str_ireplace("#LINK#", HOST.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id='.$tiket_info->tiket_id, $mail_body);
				$mail_title = \twig::$lang["tiket_mail_title"];
				$user_info = $main_users[$tiket_info->tiket_answer->id];

				sendmail::sendmail_bilder($user_info, $mail_title, $mail_body);
			}*/

			\logs::add(\twig::$lang["tiket_comment_log_add"].' (' . $comment_id . ')', \module::$log_setting);
		}

		echo json_encode(array("ref"=>ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id='.$tiket_id, "status"=>"success"));
		exit;
	}

	static function tiket_group_work() {
		self::check_permission();

		if(isset($_REQUEST['delete_group_id'])) {
			$group_id = (int)$_REQUEST['delete_group_id'];
			\DB::query("
				DELETE FROM
				" . \DB::$db_prefix . "_module_tiket_group
				WHERE tiket_group_id = '".$group_id."'
			");

			self::get_tiket_group("-1");

			\logs::add(\twig::$lang['tiket_group_log_delete'] . ' (' . $group_id . ')', \module::$log_setting);
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work');
			exit;
		}

		if(isset($_REQUEST['save'])) {

			if(isset($_REQUEST['tiket_group_title']) && mb_strlen($_REQUEST['tiket_group_title'])>=\config::app('app_min_strlen')) {

				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_tiket_group
					SET
						tiket_group_title = '".clear_text($_REQUEST['tiket_group_title'])."',
						tiket_group_edit = '".time()."'
					WHERE tiket_group_id = '".(int)$_REQUEST['tiket_group_id']."'
				");

				\logs::add(\twig::$lang['tiket_group_log_edit'] . ' (' . (int)$_REQUEST['tiket_group_id'] . ')', \module::$log_setting);
			}

			if(isset($_REQUEST['group_title_wall'])) {
				$group_title_wall = explode("\n", $_REQUEST['group_title_wall']);

				if(count($group_title_wall)>=1) {

					foreach ($group_title_wall as $key => $value) {
						if(mb_strlen($value)>=\config::app('app_min_strlen')) {
							\DB::query("
								INSERT INTO
									" . \DB::$db_prefix . "_module_tiket_group
								SET
									tiket_group_title = '".$value."',
									tiket_group_add = '".time()."',
									tiket_group_owner = '".$_SESSION['user_id']."'
							");
						}
					}

					\logs::add(\twig::$lang['tiket_group_log_add'], \module::$log_setting);
				}
			}

			self::get_tiket_group("-1");
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work');
			exit;
		}

		$tiket_group = self::get_tiket_group();
		\twig::assign('tiket_group', $tiket_group);

		\twig::assign('module_info', self::module_info());
		\twig::assign('content', \twig::fetch(self::$mod_folder . 'group.tpl'));
	}


	static function get_info_about_tiket($tiket_id) {
		$tiket_id = (int)$tiket_id;

		$tiket_info = \DB::row("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_tiket
			WHERE tiket_id = '".$tiket_id."'
		");

		if($tiket_info) {
			return tiket::tiket_info($tiket_info);
		}
		return false;
	}

	static function tiket_info($tiket_info) {
		$main_users = get_work_user();
		$tiket_group_list = self::get_tiket_group();

		$tiket_info->tiket_owner = $main_users[$tiket_info->tiket_owner];
		$tiket_info->tiket_answer = $main_users[$tiket_info->tiket_answer];
		$tiket_info->tiket_group = $tiket_group_list[$tiket_info->tiket_group];
		$tiket_info->tiket_add = unix_to_date($tiket_info->tiket_add);
		$tiket_info->tiket_edit = unix_to_date($tiket_info->tiket_edit);

		if($tiket_info->tiket_tags) {
			$tiket_info->tiket_tags_list = explode(",", $tiket_info->tiket_tags);
			end($tiket_info->tiket_tags_list);
			$tiket_info->tiket_tags_last = key($tiket_info->tiket_tags_list);
		}

		return $tiket_info;
	}

	static function comment_info($comment_info) {
		$main_users = get_work_user();

		$comment_info->comment_owner = $main_users[$comment_info->comment_owner];
		$comment_info->comment_date = unix_to_date($comment_info->comment_date);
		$comment_info->comment_text = text_to_link($comment_info->comment_text);

		return $comment_info;
	}

	static function get_tiket_group($clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : \config::app('CACHE_LIFETIME_LONG');

		$sql = \DB::fetchrow("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_tiket_group
			ORDER BY tiket_group_title ASC
		", $clear_cache);
		$tiket_group = [];
		foreach ($sql as $row) {
			$tiket_group[$row->tiket_group_id] = $row;
		}
		return $tiket_group;
	}
}
?>
