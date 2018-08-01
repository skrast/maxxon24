<?php
class group {
	static $log_setting = 1;
	static $main_title =  array();

	function __construct() {
		if(!$_SESSION['alles']) {
			access_404();
		}

		self::$main_title[] =  twig::$lang["group_name"];
	}

	static function bild_page() {
		$groups = DB::fetchrow("
			SELECT *,
			(
				SELECT COUNT(id) FROM " . DB::$db_prefix . "_users as us WHERE us.user_group = gr.user_group
			) as count
			FROM " . DB::$db_prefix . "_users_groups as gr
			ORDER BY user_group ASC
		");
		twig::assign('num', count($groups));
		twig::assign('groups', $groups);
		twig::assign('content', twig::fetch('groups.tpl'));
	}

	static function work_group() {
		$group_id = (int)$_REQUEST['group_id'];
		$main_permission = twig::$lang["main_permission"];

		$work_group = get_work_group('-1');
		$active_modules = module::$modules;

		if(isset($_REQUEST['delete']) && $group_id) {
			$group_info = $work_group[$group_id];

			if($group_info) {
				$count = DB::numrows("
					SELECT id FROM " . DB::$db_prefix . "_users us WHERE user_group = '".$group_id."'
				");

				if(!$count && $group_id != 1) {
					DB::query("
						DELETE FROM " . DB::$db_prefix . "_users_groups
						WHERE
							user_group = '".$group_id. "'
					");
					DB::query("
						DELETE FROM " . DB::$db_prefix . "_users
						WHERE
							user_group = '".$group_id. "'
					");
					logs::add(twig::$lang["group_delete_log"].' (' . $group_id . ')', self::$log_setting);
				}
			}

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=group');
			exit;
		}

		if(isset($_REQUEST['save'])) {
			$save_module = [];

			foreach ($_REQUEST['module'] as $value) {
				if(array_key_exists($value, $active_modules)) {
					$save_module[] = $value;
				}
			}

			if($group_id) { // изменение старой группы
				$group_info = $work_group[$group_id];

				if($group_info) {

					if (!empty($_REQUEST['user_group_name'])) {
						$perms = (!empty($_REQUEST['perms']) && is_array($_REQUEST['perms'])) ? implode('|', $_REQUEST['perms']) : '';
						$perms = ($group_id == '1' || in_array('alles', $_REQUEST['perms'])) ? 'alles' : $perms;

						DB::query("
							UPDATE " . DB::$db_prefix . "_users_groups
							SET
								user_group_permission = '" . $perms . "',
								user_group_desc = '" . addslashes(strip_tags($_REQUEST['user_group_desc'])) . "'
								" . (!empty($_REQUEST['user_group_name']) ? ", user_group_name = '" . addslashes(strip_tags($_REQUEST['user_group_name'])) . "'" : '') . ",
								user_group_module = '".implode(",", $save_module)."'
							WHERE user_group = '" . $group_id . "'
						");

						logs::add(twig::$lang["group_change_log"] . ' (' . $group_id . ')', self::$log_setting);
						header('Location:'.ABS_PATH_ADMIN_LINK.'?do=group&sub=work_group&group_id='.$group_id);
					} else {
						header('Location:'.ABS_PATH_ADMIN_LINK.'?do=group&sub=work_group&error=1&group_id='.$group_id);
					}
				}
				exit;
			} else { // новая группа
				if (!empty($_REQUEST['user_group_name'])) {
					$perms = (!empty($_REQUEST['perms']) && is_array($_REQUEST['perms'])) ? implode('|', $_REQUEST['perms']) : '';
					DB::query("
						INSERT INTO " . DB::$db_prefix . "_users_groups
						SET
							user_group_name = '". addslashes(strip_tags($_REQUEST['user_group_name'])) . "',
							user_group_desc = '" . addslashes(strip_tags($_REQUEST['user_group_desc'])) . "',
							user_group_permission = '" . $perms . "',
							user_group_module = '".implode(",", $save_module)."'
					");

					$group_id = DB::lastInsertId();
					logs::add(twig::$lang["group_add_log"] . ' (' . $group_id . ')', self::$log_setting);
					header('Location:'.ABS_PATH_ADMIN_LINK.'?do=group&sub=work_group&group_id='.$group_id);
					exit;
				}
			}

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=group');
			exit;
		}

		$group_info = $work_group[$group_id];
		if($group_id && !$group_info) {
			access_404();
		}


		twig::assign('active_modules', $active_modules);

		twig::assign('group_info', $group_info);
		twig::assign('group_permissions', explode('|', $group_info->user_group_permission));
		twig::assign('main_permissions', $main_permission);
		twig::assign('content', twig::fetch('group_work.tpl'));
	}
}
?>
