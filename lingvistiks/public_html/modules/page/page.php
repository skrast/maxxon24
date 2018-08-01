<?php
namespace modules;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class page {
	static $mod_folder = "";
	static $main_title =  array();
	static $uploads_dir = array(
		"preview",
	);

	function __construct() {
		self::$main_title[] =  \twig::$lang["page_name"];
	}

	static function module_info($custom='') {
		$module['module_name'] = \twig::$lang["page_name"];
		$module['module_name_short'] = \twig::$lang["page_name_short"];
		$module['module_tag'] = "page";
		$module['module_version'] = "1.0";
		$module['module_desc'] = \twig::$lang["page_description"];
		$module['module_author'] = "Максим Медведев";
		$module['module_copy'] = "&copy; 2017 Реймакс";
		$module['module_main_access'] = [1];
		$module['module_settings'] = 1;
		$module['module_allow_link'] = 1;

		self::$mod_folder = '/' . $module['module_tag'] . "/templates/";
		return ($custom!='') ? $module[$custom] : $module;
	}

	static function bild_filter() {
		// поиск
		$page_link = (isset($_REQUEST['filter'])) ? "&filter=1" : "";
		$sql_link = "";
		$start_time = "";
		$end_time = "";

		/* статусы */
		$deal_status = get_book_for_essence(3);
		\twig::assign('deal_status', $deal_status);
		/* статусы */

		if(!empty($_REQUEST['start'])) {
			$start_time = date_to_unix(urldecode($_REQUEST['start']));
			\twig::assign('start', date('d.m.Y H:i', $start_time));
		}
		if(!empty($_REQUEST['end'])) {
			$end_time = date_to_unix(urldecode($_REQUEST['end']));
			\twig::assign('end', date('d.m.Y H:i', $end_time));
		}

		if(!empty($start_time)) {
			$sql_link .= "AND page_add >= '".$start_time."' ";
			$page_link .= "&start=".urlencode(date('d.m.Y H:i', $start_time));
		}
		if(!empty($end_time)) {
			$sql_link .= "AND page_add <= '".$end_time."' ";
			$page_link .= "&end=".urlencode(date('d.m.Y H:i', $end_time));
		}

		if(!empty($_REQUEST['page_title'])) {
			$sql_link .= " AND page_title LIKE '%".clear_text($_REQUEST['page_title'])."%' ";
			$page_link .= "&page_title=".urlencode(clear_text($_REQUEST['page_title']));
		}

		// status
		$page_status = \twig::$lang['page_status_array'];
		\twig::assign('page_status', $page_status);
		// status

		// folders
		$folders = self::get_folder();
		\twig::assign('folders', $folders);
		// folders

		// фильтр
		if(!empty($_REQUEST['page_status']) && array_key_exists($_REQUEST['page_status'], $page_status)) {
			$sql_link .= "AND page_status = '".(int)$_REQUEST['page_status']."'";
			$page_link .= "&page_status=".(int)$_REQUEST['page_status'];
		}
		// фильтр

		// фильтр
		if(!empty($_REQUEST['page_folder']) && array_key_exists($_REQUEST['page_folder'], $folders)) {
			$sql_link .= "AND page_folder = '".(int)$_REQUEST['page_folder']."'";
			$page_link .= "&page_folder=".(int)$_REQUEST['page_folder'];
		}
		// фильтр

		if(!empty($_REQUEST['page_owner_id'])) {
			$sql_link .= " AND page_owner = '".(int)$_REQUEST['page_owner_id']."' ";
			$page_link .= "&page_owner_id=".(int)$_REQUEST['page_owner_id'];
		}

		return array($page_link, $sql_link);
	}

	static function page_start() {
		// сборка параметров для поиска
		$bild_filter = self::bild_filter();
		$page_link = $bild_filter[0];
		$sql_link = $bild_filter[1];
		// сборка параметров для поиска

		/* пользователи */
		$main_users = get_work_user();
		\twig::assign('main_users', $main_users);
		/* пользователи */

		// pager
		$start = get_current_page() * \config::app('SYS_PEAR_PAGE') - \config::app('SYS_PEAR_PAGE');
		// pager

		$pages = \DB::fetchrow("
			SELECT
				*
			FROM " . \DB::$db_prefix . "_module_page
			WHERE 1=1 $sql_link
			ORDER BY page_id DESC
			LIMIT " . $start . "," . \config::app('SYS_PEAR_PAGE') . "
		");
		foreach ($pages as $key => $value) {
			$value = self::page_info($value);
		}
		\twig::assign('pages', $pages);

		// pager
		$num = \DB::numrows("
			SELECT
				page_id
			FROM " . \DB::$db_prefix . "_module_page
			WHERE 1=1 $sql_link
		");
		\twig::assign('num', $num);
		if ($num > \config::app('SYS_PEAR_PAGE'))
		{
			$page_nav = get_pagination(ceil($num / \config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=module&sub=mod_edit&module_tag=page&module_action=page_start'.$page_link.'&page={s}">{t}</a>');
			\twig::assign('page_nav', $page_nav);
		}
		// pager

		\twig::assign('content', \twig::fetch(self::$mod_folder.'page_start.tpl'));
	}

	static function page_work() {
		$module_info = self::module_info();
		\twig::assign('module_info', $module_info);

		if(isset($_REQUEST['page_id'])) {
			$page_id = (int)$_REQUEST['page_id'];
			$page_info = self::get_info_about_page($page_id);

			if($page_id && !$page_info &&
				(
					!$_SESSION['alles'] && $_SESSION['user_id'] != $page_info->page_owner->id
				)
			) {
				access_404();
			}

			// photos
			$page_photo = self::get_page_photo($page_info->page_id);
			\twig::assign('page_photo', $page_photo);
			// photos

			\twig::assign('page_info', $page_info);
		}

		// status
		$page_status = \twig::$lang['page_status_array'];
		\twig::assign('page_status', $page_status);
		// status

		// доступные языковые версии
		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);
		// доступные языковые версии

		// folders
		$folders = self::get_folder();
		\twig::assign('folders', $folders);
		// folders


		if($_REQUEST['save']) {
			$error = [];

			$page_title = strip_tags(addslashes($_REQUEST['page_title']));
			$page_alias = strip_tags(addslashes($_REQUEST['page_alias']));
			$page_lang = strip_tags(addslashes($_REQUEST['page_lang']));
			$page_tags = strip_tags(addslashes($_REQUEST['page_tags']));
			$page_youtube = addslashes($_REQUEST['page_youtube']);
			$page_meta_description = strip_tags(addslashes($_REQUEST['page_meta_description']));
			$page_meta_keywords = strip_tags(addslashes($_REQUEST['page_meta_keywords']));
			$page_meta_robots = strip_tags(addslashes($_REQUEST['page_meta_robots']));
			$page_text = clear_tags($_REQUEST['page_text']);
			$page_landing_sourse = addslashes($_REQUEST['page_landing_sourse']);

			$page_status = (array_key_exists($_REQUEST['page_status'], $page_status)) ? (int)$_REQUEST['page_status'] : 0;
			$page_folder = (int)$_REQUEST['page_folder'];

			$page_tags = explode(",", $page_tags);
			$tags_temp = [];
			foreach ($page_tags as $key => $value) {
				if(mb_strlen(trim($value)) >= \config::app('app_min_strlen')) {
					$tags_temp[] = trim($value);
				}
			}
			$page_tags = array_unique($tags_temp);

			// bild_url
			if ($page_id != 1 && $_REQUEST['page_index'] != 1) {
				$page_alias = $_url = prepare_url(empty($page_alias)
					? trim($page_title, '/')
					: trim($page_alias, '/')
				);
			} else {
				$page_alias = "/";
			}
			// bild_url

			try {
			    v::length(\config::app('app_min_strlen'), null)->assert($page_title);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_title"];
			}

			$page_alias_check = \DB::numrows("
				SELECT page_id FROM
					" . \DB::$db_prefix . "_module_page
				WHERE
					page_lang = '".$page_lang."' AND page_alias = '".$page_alias."' ". (($page_id) ? " AND page_id != '".$page_id."' " : "") ."
			");

			try {
				v::intVal()->max(0)->assert($page_alias_check);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_url_double"];
			}
			try {
				v::length(1, null)->assert($page_alias);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_url"];
			}

			try {
				v::in($lang_array)->assert($page_lang);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_lang"];
			}

			try {
				v::in($page_meta_robots)->assert(\twig::$lang["page_meta_robots_array"]);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_robot"];
			}

			try {
				v::key($page_folder)->assert($folders);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_folder"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(\backend::isAjax()) {
				echo json_encode(array("upload"=>true, "status"=>"success"));
				exit;
			}

			$page_youtube = self::get_link_youtube($page_youtube);

			if($_REQUEST['page_index'] == 1) {
				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_page
					SET
						page_index = '0'
					WHERE
						page_lang = '".$page_lang."'
				");
			}

			if(isset($_REQUEST['page_id'])) {
				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_page
					SET
						page_title = '".$page_title."',
						page_alias = '".$page_alias."',
						page_meta_description = '".$page_meta_description."',
						page_meta_keywords = '".$page_meta_keywords."',
						page_meta_robots = '".$page_meta_robots."',
						page_lang = '".$page_lang."',
						page_index = '".($_REQUEST['page_index'] ? 1 : 0)."',
						page_landing = '".($_REQUEST['page_landing'] ? 1 : 0)."',
						page_landing_in_site = '".($_REQUEST['page_landing_in_site'] ? 1 : 0)."',
						page_text = '".$page_text."',
						page_landing_sourse = '".$page_landing_sourse."',
						page_edit = '".time()."',
						page_edit_author = '".$_SESSION['user_id']."',
						page_status = '".$page_status."',
						page_youtube = '".$page_youtube."',
						page_folder = '".$page_folder."'
					WHERE
						page_id = '".$page_id."'
				");

				self::get_info_about_page($page_id, "-1");

				\logs::add(\twig::$lang["page_log_edit"].' (' . $page_id . ')', \module::$log_setting);

			} else {
				\DB::query("
					INSERT INTO
						" . \DB::$db_prefix . "_module_page
					SET
						page_title = '".$page_title."',
						page_alias = '".$page_alias."',
						page_meta_description = '".$page_meta_description."',
						page_meta_keywords = '".$page_meta_keywords."',
						page_meta_robots = '".$page_meta_robots."',
						page_lang = '".$page_lang."',
						page_index = '".($_REQUEST['page_index'] ? 1 : 0)."',
						page_landing = '".($_REQUEST['page_landing'] ? 1 : 0)."',
						page_landing_in_site = '".($_REQUEST['page_landing_in_site'] ? 1 : 0)."',
						page_text = '".$page_text."',
						page_landing_sourse = '".$page_landing_sourse."',
						page_add = '".time()."',
						page_owner = '".$_SESSION['user_id']."',
						page_status = '".$page_status."',
						page_youtube = '".$page_youtube."',
						page_folder = '".$page_folder."'
				");
				$page_id = \DB::lastInsertId();
				\logs::add(\twig::$lang["page_log_add"].' (' . $page_id . ')', \module::$log_setting);
			}

			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_page_tags
				WHERE page_id = '".$page_id."'
			");

			foreach ($page_tags as $value) {
				\DB::query("
					INSERT INTO
						" . \DB::$db_prefix . "_module_page_tags
					SET
						page_id = '".$page_id."',
						tags_title = '".$value."',
						tags_type = '".$page_folder."'
				");
			}

			// preview
			$targetPath = BASE_DIR."/".\config::app('app_upload_dir')."/".\config::app('app_module_dir')."/" . $module_info['module_tag'] ."/preview/" . $page_id . "/";
		    if(!file_exists($targetPath)) {
				@mkdir($targetPath, 0777);
			}

			if (!empty($_FILES['file_path']['name'])) {
		        $file_data = $_FILES['file_path']['name']; // файл основа
		        $file_tmp = $_FILES['file_path']['tmp_name']; // изображения для данных
		        $file_data = rename_file($file_data, $targetPath); // переименовываем

				$file_type = file_get_info($file_data);

		        if ($file_data != false && in_array($file_type['extension'], \config::app('app_allow_img'))) {
		            $targetFile =  $targetPath . $file_data;

					if($page_info->page_preview) {
						unlink($targetPath.$page_info->page_preview);
					}

		            if(move_uploaded_file($file_tmp, $targetFile)) {
		            	\DB::query("
							UPDATE
								" . \DB::$db_prefix . "_module_page
							SET
								page_preview = '".clear_text($file_data)."'
							WHERE page_id = '".$page_id."'
						");
		            }
			  	}
		    }
			// preview

			if($_REQUEST['file_delete']) {

				$photos = \DB::fetchrow("
					SELECT * FROM
						" . \DB::$db_prefix . "_module_page_files
					WHERE
						file_id IN (".addslashes(implode(',', $_REQUEST['file_delete'])).")
				");
				$targetPath = BASE_DIR."/".\config::app('app_upload_dir')."/".\config::app('app_module_dir')."/" . $module_info['module_tag'] ."/photos/" . $page_id . "/";

				foreach ($photos as $row) {
					unlink($targetPath . $row->file_path);
				}

				\DB::query("
					DELETE FROM
						" . \DB::$db_prefix . "_module_page_files
					WHERE
						file_id IN (".addslashes(implode(',', $_REQUEST['file_delete'])).")
				");
			}


			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=page_work&page_id='.$page_id);
			exit;
		}


		\twig::assign('content', \twig::fetch(self::$mod_folder.'page_work.tpl'));
	}

	static function get_page_photo($page_id) {

		$photos = \DB::fetchrow("
			SELECT * FROM
				" . \DB::$db_prefix . "_module_page_files
			WHERE
				file_page = '".$page_id."'
			ORDER BY file_date DESC
		");

		return $photos;
	}

	static function page_work_photo_uploads() {
		$module_info = self::module_info();

		$page_id = (int)$_REQUEST['page_id'];
		$page_info = self::get_info_about_page($page_id);

		if($page_id && !$page_info &&
			(
				!$_SESSION['alles'] && $_SESSION['user_id'] != $page_info->page_owner->id
			)
		) {
			exit;
		}

		$targetPath = BASE_DIR."/".\config::app('app_upload_dir')."/".\config::app('app_module_dir')."/" . $module_info['module_tag'] ."/photos/" . $page_id . "/";
	    if(!file_exists($targetPath)) {
			@mkdir($targetPath, 0777);
		}

		if (!empty($_FILES['page_photo']['name'])) {
	        $file_data = $_FILES['page_photo']['name']; // файл основа
	        $file_tmp = $_FILES['page_photo']['tmp_name']; // изображения для данных
	        $file_data = rename_file($file_data, $targetPath); // переименовываем

			$file_type = file_get_info($file_data);
			if(!in_array($file_type['extension'], \config::app('app_allow_img'))) {
				exit;
			}

	        $use_original_name = explode(".", $_FILES['page_photo']['name']);
	        array_pop($use_original_name);
	        $use_original_name = implode(".", $use_original_name);

	        if(mb_strlen($use_original_name) < \config::app('app_min_strlen')) {
				$use_new_name = explode(".", $file_data);
	        	$use_original_name = $use_new_name[0];
	        }

	        if ($file_data != false) {
	            $targetFile =  $targetPath . $file_data;
	            if(move_uploaded_file($file_tmp, $targetFile)) {
	            	\DB::query("
						INSERT INTO
							" . \DB::$db_prefix . "_module_page_files
						SET
							file_path = '".$file_data."',
							file_page = '".$page_id."',
							file_date = '".time()."',
							file_name = '".clear_text($use_original_name)."'
					");
	            }
		  	}
		  	echo '{"status":"success"}';
	    }
	    exit;
	}

	static function mass_change() {
    	$elem_opt = $_REQUEST['elem_opt'];
    	$mass_do = $_REQUEST['operation'];

    	if(count($elem_opt) >= 1) {
    		switch ($mass_do) {
    			case '1':
    				foreach ($elem_opt as $key => $value) {
				    	self::delete_page((int)$value);
    				}
    			break;

    			case '2':
    				foreach ($elem_opt as $key => $value) {
    					$page_info = self::get_info_about_page((int)$value);

    					if(
							$page_info &&
							($page_info->page_owner == $_SESSION['user_id'] || $_SESSION['alles'])
						) {
				    		\DB::query("
								UPDATE
									" . \DB::$db_prefix . "_module_page
								SET
									page_status = '1',
									page_edit = '".time()."',
									page_edit_author = '".$_SESSION['user_id']."'
								WHERE
									page_id = '".$page_info->page_id."'
							");
							self::get_info_about_page($page_info->page_id, "-1");
				    	}
    				}
    			break;

    			case '3':
    				foreach ($elem_opt as $key => $value) {
				    	$page_info = self::get_info_about_page((int)$value);

    					if(
							$page_info &&
							($page_info->page_owner == $_SESSION['user_id'] || $_SESSION['alles'])
						) {
				    		\DB::query("
								UPDATE
									" . \DB::$db_prefix . "_module_page
								SET
									page_status = '0',
									page_edit = '".time()."',
									page_edit_author = '".$_SESSION['user_id']."'
								WHERE
									page_id = '".$page_info->page_id."'
							");
							self::get_info_about_page($page_info->page_id, "-1");
				    	}
    				}
    			break;

    			case '5':
    				foreach ($elem_opt as $key => $value) {
				    	$page_info = self::get_info_about_page((int)$value);

				    	$folders = self::get_folder();

    					if(
							$page_info &&
							array_key_exists($_REQUEST['page_folder'], $folders) &&
							($page_info->page_owner == $_SESSION['user_id'] || $_SESSION['alles'])
						) {
				    		\DB::query("
								UPDATE
									" . \DB::$db_prefix . "_module_page
								SET
									page_folder = '".(int)$_REQUEST['page_folder']."',
									page_edit = '".time()."',
									page_edit_author = '".$_SESSION['user_id']."'
								WHERE
									page_id = '".$page_info->page_id."'
							");
							self::get_info_about_page($page_info->page_id, "-1");
				    	}
    				}
    			break;
    		}
    	}

    	header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=page_start');
    	exit;
    }

	static function page_delete() {
		self::delete_page((int)$_REQUEST['page_id']);
		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=page_start');
		exit;
	}


	static function delete_page($page_id) {
		$page_info = self::get_info_about_page($page_id);

		if(
			$page_info &&
			($page_info->page_owner == $_SESSION['user_id'] || $_SESSION['alles'])
		) {
			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_page
				WHERE page_id = '".(int)$page_id."'
			");

			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_page_tags
				WHERE page_id = '".(int)$page_id."'
			");

			\logs::add(\twig::$lang["page_log_delete"].' (' . $page_id . ')', \module::$log_setting);
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=page_start');
		exit;
	}


	static function get_info_about_page($page_id, $clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : \config::app('CACHE_LIFETIME_LONG');

		$page = \DB::row("
			SELECT
			page.*,
			GROUP_CONCAT(dt.tags_title) as tags_title
			FROM
				" . \DB::$db_prefix . "_module_page as page
			LEFT JOIN " . \DB::$db_prefix . "_module_page_tags dt ON dt.page_id = page.page_id
			WHERE page.page_id = '".(int)$page_id."'
		", $clear_cache);

		$page = self::page_info($page);
		return $page;
	}

	static function page_info($page_info) {
		$module_info = self::module_info();
		$work_user = get_work_user();
		$folders = self::get_folder();

		$page_info->page_add = unix_to_date($page_info->page_add);
		if(isset($page_info->page_edit) && $page_info->page_edit) {
			$page_info->page_edit = unix_to_date($page_info->page_edit);
		}

		$page_info->page_owner = $work_user[$page_info->page_owner];
		if(isset($page_info->page_edit_author) && $page_info->page_edit_author) {
			$page_info->page_edit_author = $work_user[$page_info->page_edit_author];
		}

		if(isset($page_info->page_folder) && $page_info->page_folder) {
			$page_info->page_folder = $folders[$page_info->page_folder];
		}

		if($page_info->tags_title) {
			$page_info->tags_title = explode(",", $page_info->tags_title);
			$page_info->page_tags = implode(", ", $page_info->tags_title);
		}

		$page_info->page_alias_clear = trim($page_info->page_alias, '/');

		if($page_info->page_lang != \config::app('app_lang')) {
			$page_info->page_alias = $page_info->page_lang . "/" . trim($page_info->page_alias, '/');
		}

		if($page_info->page_lang != 1 && $page_info->page_index != 1) {
			$page_info->page_alias = trim($page_info->page_alias, '/') . \config::app("URL_SUFF");
		}

		if($page_info->page_index == 1) {
			$page_info->page_alias = $page_info->page_lang . "/";
		}

		if($page_info->page_id == 1) {
			$page_info->page_alias = "";
		}

		$page_info->page_preview_site =  ($page_info->page_preview) ? \config::app('app_upload_dir')."/".\config::app('app_module_dir')."/" . $module_info['module_tag'] ."/preview/" . $page_info->page_id . "/" . $page_info->page_preview : \config::app('app_upload_dir') . "/no-preview.jpg";


		return $page_info;
	}


	static function folder_work() {

		if(isset($_REQUEST['folder_title_new'])) {
			$folder_title_new = strip_tags(addslashes($_REQUEST['folder_title_new']));

			if($folder_title_new) {
				\DB::query("
					INSERT INTO " . \DB::$db_prefix . "_module_page_folder
					SET
						folder_title = '". $folder_title_new . "',
						folder_add = '". time() . "',
						folder_add_author = '". $_SESSION['user_id'] . "'
				");

				\logs::add(\twig::$lang['page_log_add_folder'], \module::$log_setting);
			}
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=folder_work');
			exit;
		}

		if(isset($_REQUEST['folder_title'])) {
			$owner = (!isset($_SESSION['alles'])) ?  " AND (folder_add_author = '".$_SESSION['user_id']."') " : '';

			foreach ($_REQUEST['folder_title'] as $id => $folder_title)
			{
				$folder_title = strip_tags(addslashes($folder_title));

				if (!empty($folder_title))
				{
					\DB::query("
						UPDATE
							" . \DB::$db_prefix . "_module_page_folder
						SET
							folder_title = '". $folder_title . "',
							folder_edit = '". time() . "',
							folder_edit_author = '". $_SESSION['user_id'] . "'
						WHERE
							folder_id = '".(int)$id."' $owner
					");
				}
			}

			\logs::add(\twig::$lang['page_log_edit_folder'], \module::$log_setting);
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=folder_work');
			exit;
		}

		$folders = self::get_folder("-1");
		\twig::assign('folders', $folders);
		\twig::assign('content', \twig::fetch(self::$mod_folder.'folder_work.tpl'));
	}

	static function get_folder($clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : \config::app('CACHE_LIFETIME_LONG');

		$sql = \DB::fetchrow("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_page_folder
			ORDER BY folder_title ASC
		", $clear_cache);
		foreach ($sql as $row) {
			$folders[$row->folder_id] = $row;
		}

		return $folders;
	}

	static function folder_delete() {
		$folder_id = (int)$_REQUEST['folder_id'];

		$folder_info = \DB::row("
			SELECT * FROM
				" . \DB::$db_prefix . "_module_page_folder
			WHERE folder_id = '".$folder_id."'
		");

		$folder_count = \DB::numrows("
			SELECT page_id FROM
				" . \DB::$db_prefix . "_module_page
			WHERE page_folder = '".$folder_id."'
		");

		if(
			$folder_count == 0 &&
			$folder_info &&
			($folder_info->folder_add_author == $_SESSION['user_id'] || $_SESSION['alles'])
		) {
			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_page_folder
				WHERE folder_id = '".$folder_id."'
			");

			\DB::query("
				UPDATE
					" . \DB::$db_prefix . "_module_page
				SET
					page_folder = '0'
				WHERE page_folder = '".$folder_id."'
			");

			\logs::add(\twig::$lang["page_log_delete_folder"].' (' . $folder_id . ')', \module::$log_setting);
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=folder_work');
	}

	static function navi_start() {
		// доступные языковые версии
		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);
		// доступные языковые версии

		// расположение меню
		$place_array = \config::app('site_menu_place');
		\twig::assign('place_array', $place_array);
		// расположение меню

		$navi = \DB::fetchrow("
			SELECT
				*
			FROM " . \DB::$db_prefix . "_module_page_navi
			ORDER BY navi_id DESC
		");
		\twig::assign('navi', $navi);

		\twig::assign('content', \twig::fetch(self::$mod_folder.'navi_start.tpl'));
	}

	static function navi_work() {
		if(isset($_REQUEST['navi_id'])) {
			$navi_id = (int)$_REQUEST['navi_id'];
			$navi_info = self::get_info_about_navi($navi_id);
			\twig::assign('navi_info', $navi_info);

			if($navi_id && !$navi_info) {
				access_404();
			}
		}

		// доступные языковые версии
		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);
		// доступные языковые версии

		// расположение меню
		$place_array = \config::app('site_menu_place');
		\twig::assign('place_array', $place_array);
		// расположение меню

		if($_REQUEST['save']) {
			$error = [];

			$navi_title = strip_tags(addslashes($_REQUEST['navi_title']));
			$navi_lang = strip_tags(addslashes($_REQUEST['navi_lang']));
			$navi_place = (int)$_REQUEST['navi_place'];

			try {
			    v::length(\config::app('app_min_strlen'), null)->assert($navi_title);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_title"];
			}

			try {
				v::in($lang_array)->assert($navi_lang);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_lang"];
			}

			try {
				v::key($navi_place)->assert($place_array);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_place"];
			}

			$check_copy = \DB::numrows("
				SELECT navi_id FROM
					" . \DB::$db_prefix . "_module_page_navi
				WHERE
					navi_lang = '".$navi_lang."' AND navi_place = '".$navi_place."' ".($navi_id ? " AND navi_id != '".$navi_id."'" : "")."
			");

			try {
			    v::intVal()->max(0)->assert($check_copy);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_place_copy"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(\backend::isAjax()) {
				echo json_encode(array("upload"=>true, "status"=>"success"));
				exit;
			}

			if(isset($_REQUEST['navi_id'])) {
				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_page_navi
					SET
						navi_title = '".$navi_title."',
						navi_place = '".$navi_place."',
						navi_lang = '".$navi_lang."'
					WHERE
						navi_id = '".$navi_id."'
				");

				\logs::add(\twig::$lang["page_navi_log_edit"].' (' . $navi_id . ')', \module::$log_setting);

			} else {
				\DB::query("
					INSERT INTO
						" . \DB::$db_prefix . "_module_page_navi
					SET
						navi_title = '".$navi_title."',
						navi_place = '".$navi_place."',
						navi_lang = '".$navi_lang."'
				");
				$navi_id = \DB::lastInsertId();
				\logs::add(\twig::$lang["page_navi_log_add"].' (' . $navi_id . ')', \module::$log_setting);
			}

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=navi_work&navi_id='.$navi_id);
			exit;
		}

		\twig::assign('content', \twig::fetch(self::$mod_folder.'navi_work.tpl'));
	}

	static function get_info_about_navi($navi_id) {
		$navi_id = (int)$navi_id;

		$navi_info = \DB::row("
			SELECT * FROM
				" . \DB::$db_prefix . "_module_page_navi
			WHERE navi_id = '".$navi_id."'
		");

		if($navi_info) {
			$navi_info->navi_items = self::get_child_navi_item($navi_id);

			return $navi_info;
		}
        return false;
	}

	static function get_child_navi_item($navi_id, $clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : \config::app('CACHE_LIFETIME_LONG');

		$sql = \DB::fetchrow("
			SELECT
			*
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE navi_id = '".$navi_id."'
			ORDER BY item_sort ASC
		", $clear_cache);
		$navi_item = [];

		foreach ($sql as $row) {
			if($row->item_page) {
				$row->page_info = self::get_info_about_page($row->item_page);
			}
			$navi_item[$row->parent_id][] = $row;
		}

		return $navi_item;
	}

	// сохранение позиций меню
	static function navi_sort() {
		$nest = json_decode(stripslashes($_REQUEST['nest']));
		$essense_id = (int)$_REQUEST['essense_id'];

		$navi_info = \DB::row("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_page_navi
			WHERE navi_id = '".$essense_id."'
		");

		if(!$navi_info) {
			access_404();
		}

		self::navi_sort_save($nest);
		self::get_child_navi_item($essense_id, "-1");
		echo json_encode(array("respons"=>"", "status"=>"success"));
		exit;
	}
	static function navi_sort_save($nest, $parent_id=0) {
		$essense_id = (int)$_REQUEST['essense_id'];
        //$navi_list = self::get_child_navi_item($essense_id);

		foreach ($nest as $key => $value) {
			/*if((int)$parent_id!=(int)$value->id && ($parent_id!=$navi_list[$value->id]->parent_id || $key!=$navi_list[$value->id]->item_sort)) {*/
				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_page_navi_item
					SET
						item_sort = '".(int)$key."',
						parent_id = '".(int)$parent_id."'
					WHERE item_id = '".(int)$value->id."'
				");
			/*}*/

			if($value->children) {
				self::navi_sort_save($value->children, $value->id);
			}
		}
	}
	// сохранение позиций меню

	static function search_page() {
		$search = clear_text($_REQUEST['query']);
    	$suggestions = array();

		$sql_where = "";
    	if(mb_strlen($search)<\config::app('app_min_strlen')) {

    	} else {
			$sql_where = " AND page_title LIKE '%".$search."%' ";
		}

		$sql = \DB::fetchrow("
			SELECT page_id, page_title
			FROM
				" . \DB::$db_prefix . "_module_page
			WHERE
				1=1 $sql_where
			ORDER BY page_id DESC
			LIMIT 5
		");
		foreach ($sql as $row) {
			$suggestions[] = array("value"=>$row->page_title, "data"=>$row);
		}

		$respon = array(
			'query'=>$search,
			'suggestions'=>$suggestions,
		);

		echo json_encode($respon);
		exit;
	}

	static function navi_work_item() {
		if(isset($_REQUEST['navi_id'])) {
			$navi_id = (int)$_REQUEST['navi_id'];
			$navi_info = self::get_info_about_navi($navi_id);
			if($navi_id && !$navi_info) {
				access_404();
			}
		}

		if($_REQUEST['save']) {
			$error = [];

			$item_title = strip_tags(addslashes($_REQUEST['item_title']));

			$item_page = (int)$_REQUEST['item_page'];
			$item_id = (int)$_REQUEST['item_id'];
			$page_info = self::get_info_about_page($item_page);
			if($item_page && !$page_info) {
				access_404();
			}

			try {
			    v::length(\config::app('app_min_strlen'), null)->assert($item_title);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_title"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(\backend::isAjax()) {
				echo json_encode(array("upload"=>true, "status"=>"success"));
				exit;
			}

			if(isset($_REQUEST['item_id'])) {
				$check = \DB::row("
					SELECT *
					FROM " . \DB::$db_prefix . "_module_page_navi_item
					WHERE item_id = '".(int)$_REQUEST['item_id']."'
				");
				if(!$check) {
					access_404();
				}

				\DB::query("
					UPDATE
						" . \DB::$db_prefix . "_module_page_navi_item
					SET
						item_page = '".$item_page."',
						item_title = '".$item_title."'
					WHERE
						item_id = '".$item_id."'
				");

				self::get_child_navi_item($check->navi_id, "-1");
			} else {

				$last = \DB::single("
					SELECT item_sort FROM
						" . \DB::$db_prefix . "_module_page_navi_item
					WHERE
						navi_id = '".$navi_id."' AND parent_id = '0'
					ORDER BY item_sort DESC
				");

				\DB::query("
					INSERT INTO
						" . \DB::$db_prefix . "_module_page_navi_item
					SET
						item_page = '".$item_page."',
						item_title = '".$item_title."',
						navi_id = '".$navi_id."',
						item_sort = '".($last+1)."'
				");

				self::get_child_navi_item($navi_id, "-1");
			}
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=navi_work&navi_id='.$navi_id);
		exit;
	}

	static function edit_item() {
		$module_info = self::module_info();

		$void_id = (int)$_REQUEST['void_id'];
		$essense_id = (int)$_REQUEST['essense_id'];

		$navi_info = self::get_info_about_navi($essense_id);
		$item_info = \DB::row("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE item_id = '".$void_id."'
		");
		if(!$item_info || !$navi_info) {
			access_404();
		}

		\twig::assign('navi_info', $navi_info);
		\twig::assign('item_info', $item_info);

		$html = \twig::fetch(self::$mod_folder . 'item_work.tpl');
		echo json_encode(array("title"=>\twig::$lang["page_navi_edit"],"html"=>$html, "status"=>"success"));
		exit;
	}

	static function item_delete() {
		$item_id = (int)$_REQUEST['item_id'];

		$item_info = \DB::row("
			SELECT *
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE item_id = '".$item_id."'
		");
		if(!$item_info) {
			access_404();
		}

		\DB::query("
			DELETE
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE parent_id = '".$item_id."'
		");

		\DB::query("
			DELETE
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE parent_id IN (SELECT item_id
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE parent_id = '".$item_id."')
		");

		\DB::query("
			DELETE
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE item_id = '".$item_id."'
		");

		self::get_child_navi_item($item_info->navi_id, "-1");

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=navi_work&navi_id='.$item_info->navi_id);
		exit;
	}

	static function navi_delete() {
		$navi_id = (int)$_REQUEST['navi_id'];
		$navi_info = self::get_info_about_navi($navi_id);
		if(!$navi_info) {
			access_404();
		}

		\DB::query("
			DELETE
			FROM " . \DB::$db_prefix . "_module_page_navi_item
			WHERE navi_id = '".$navi_id."'
		");

		\DB::query("
			DELETE
			FROM " . \DB::$db_prefix . "_module_page_navi
			WHERE navi_id = '".$navi_id."'
		");

		self::get_child_navi_item($navi_info->navi_id, "-1");

		\logs::add(\twig::$lang["page_navi_log_delete"].' (' . $navi_id . ')', \module::$log_setting);

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=page&module_action=navi_start');
		exit;
	}

	static function install() {
		$module_info = self::module_info();

		$upload_folder = BASE_DIR . '/' . \config::app('app_upload_dir') . '/' . \config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/';
		foreach (self::$uploads_dir as $value) {
			if(!is_dir($upload_folder.$value."/")) {
				@mkdir($upload_folder.$value."/");
				@chmod($upload_folder.$value."/", 0755);
			}
		}
	}

	static function uninstall() {
		$module_info = self::module_info();
		$upload_folder = BASE_DIR . '/' . \config::app('app_upload_dir') . '/' . \config::app('app_module_dir') .'/'. $module_info['module_tag'] .'/';

		foreach (self::$uploads_dir as $value) {
			rrmdir($upload_folder.$upload_folder);
		}
	}


	function get_link_youtube($link) {
		$post_youtube_save = "";
		if(filter_var($link, FILTER_VALIDATE_URL) && strripos($link, "youtu.be")) {
			$link = explode("/", $link);
			$post_youtube_save = end($link);
		}

		if(filter_var($link, FILTER_VALIDATE_URL) && strripos($link, "youtube.com/watch?v=")) {
			$link = explode("=", $link);
			$post_youtube_save = end($link);
		}

		if(filter_var($link, FILTER_VALIDATE_URL) && strripos($link, "youtube.com/embed/")) {
			$link = explode("/", $link);
			$post_youtube_save = end($link);
		}

		$get_link = "https://www.youtube.com/embed/".$post_youtube_save;

		return ($get_link == "https://www.youtube.com/embed/") ? "" : $get_link;
	}
}
?>
