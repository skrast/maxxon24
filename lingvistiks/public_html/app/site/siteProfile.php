<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteProfile {
	static $main_title = [];

	static function user_profile() {
		// title
		$page_info->page_title = twig::$lang['lk_title'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		if(!$profile_info || !$_SESSION['user_id']) {
			site::error404();
		} else {

			if($_REQUEST['crop_photo']) {
				self::cropPhoto();
			}

			if($_REQUEST['save']) {
			    
			    // TODO: if service list is empty (before first saving)
			    $profile_info->{'aux_user_service_count_temp'} = is_array($_REQUEST['serv_service']) ? count($_REQUEST['serv_service']): 0;
			    // check a user have appropriate acl
			    if (!(new CallBackHelper($profile_info))(ActionEnum::ServicesCreate)) {
			        
			        if (backend::isAjax()) {
			            echo json_encode(array("upload"=>true, "status"=>"success"));
			            exit;
			        }
			        
			        return;
			    } else {
			        self::saveProfile();
			    }
			    
			}

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			$city_list = city_by_lang($profile_info->user_country);
			twig::assign('city_list', $city_list);

			// временные зоны
			$zone = [];
			for ($i=1; $i <= 12; $i++) { 
				$zone["+".$i] = "UTC+".$i;
			}
			$zone[0] = "UTC0";
			for ($i=1; $i <= 12; $i++) { 
				$zone["-".$i] = "UTC-".$i;
			}
			twig::assign('zone', $zone);

			/*$zone = DB::fetchrow("
				SELECT city_id, city_utm, city_country
				FROM " . DB::$db_prefix . "_module_city
				GROUP BY city_utm
				ORDER BY city_utm ASC
			", config::app('CACHE_LIFETIME_LONG'));
			twig::assign('zone', $zone);*/
			// временные зоны

			$app_allow_ext = array_keys(\config::app('app_allow_ext'));
			$app_allow_ext = implode(", ", $app_allow_ext);
			$app_allow_ext = str_replace("|", ", ", $app_allow_ext);
			\twig::assign('app_allow_ext', $app_allow_ext);

			$app_allow_img = config::app('app_allow_img');
			twig::assign('app_allow_img', $app_allow_img);

			// альбом
			$album_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_album
				WHERE file_owner = '". $profile_id ."'
			");
			twig::assign('album_list', $album_list);
			// альбом

			if($profile_info->user_type_form == 2) {
				$company_info = DB::row("
					SELECT * FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$_SESSION['user_id']."'
				");
				twig::assign('company_info', $company_info);
			}

			// шаблон страницы
			switch ($_SESSION['user_group']) {
				case '3':

					// языковые пары
					$lang_var = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_langvar
						WHERE var_owner = '". $profile_id ."'
					");
					foreach ($lang_var as $row) {
						$row->var_lang_from = $lang_list[$row->var_lang_from];
						$row->var_lang_to = $lang_list[$row->var_lang_to];
					}
					twig::assign('lang_var', $lang_var);
					// языковые пары


					$book_list_1 = get_book_for_essence(6);
					$book_list_2 = get_book_for_essence(15);
					$book_list_3 = get_book_for_essence(16);
					twig::assign('book_list_1', $book_list_1);
					twig::assign('book_list_2', $book_list_2);
					twig::assign('book_list_3', $book_list_3);


					// справочник по уровням владения языком
					$lang_level = get_book_for_essence(14);
					twig::assign('lang_level', $lang_level);
					// справочник по уровням владения языком

					// справочник по возрасту обучаемых
					$lang_age = get_book_for_essence(19);
					twig::assign('lang_age', $lang_age);
					// справочник по возрасту обучаемых

					// справочник по Перечень оказываемых услуг
					$lang_service = get_book_for_essence(20);
					twig::assign('lang_service', $lang_service);
					// справочник по Перечень оказываемых услуг

					// справочник по темам
					$theme_list = get_book_for_essence(2);
					twig::assign('theme_list', $theme_list);
					// справочник по темам

					// справочник по опыту работы
					$experience_list = get_book_for_essence(3);
					twig::assign('experience_list', $experience_list);
					// справочник по опыту работы

					// справочник по средствам связи
					$communication_list = get_book_for_essence(4);
					twig::assign('communication_list', $communication_list);
					// справочник по средствам связи

					// справочник по опыту работы
					$currency_list = get_book_for_essence(5);
					twig::assign('currency_list', $currency_list);
					// справочник по опыту работы

					// справочник по видам услуг
					$service_list = get_book_for_essence(6);
					twig::assign('service_list', $service_list);
					// справочник по видам услуг

					// справочник по видам услуг
					/*$service_type_list = get_book_for_essence(7);
					twig::assign('service_type_list', $service_type_list);*/
					// справочник по видам услуг

					// справочник по опыту работы
					$time_list = get_book_for_essence(8);
					twig::assign('time_list', $time_list);
					// справочник по опыту работы

					// справочник по Место занятий
					$place_work_list = get_book_for_essence(17);
					twig::assign('place_work_list', $place_work_list);
					// справочник по Место занятий

					// справочник по Место занятий
					$level_list = get_book_for_essence(14);
					twig::assign('level_list', $level_list);
					// справочник по Место занятий

					// услуги
					$sql = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_services
						WHERE serv_owner = '". $profile_id ."'
					");
					$serv_list = [];
					foreach ($sql as $row) {

						$book_id = siteOrder::$book_link[$row->serv_service];
						$service_type_list = get_book_for_essence($book_id);

						$row->serv_lang_from = $lang_list[$row->serv_lang_from];
						$row->serv_lang_to = $lang_list[$row->serv_lang_to];
						$row->serv_communication = $communication_list[$row->serv_communication];
						$row->serv_place = $place_work_list[$row->serv_place];
						$row->serv_theme = $theme_list[$row->serv_theme];
						$row->serv_level = $level_list[$row->serv_level];
						$row->serv_currency = $currency_list[$row->serv_currency];
						$row->serv_type_service = $service_type_list[$row->serv_type_service];
						$row->serv_time = $time_list[$row->serv_time];
						$row->serv_coast = format_string_number($row->serv_coast);

						$serv_list[$row->serv_service][] = $row;
					}
					twig::assign('serv_list', $serv_list);
					// услуги

					// скилл
					$sql = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_skill
						WHERE skill_owner = '". $profile_id ."'
					");
					$skill_list = [];
					foreach ($sql as $row) {
						$skill_list[$row->skill_type] = $row;
					}
					twig::assign('skill_list', $skill_list);
					// скилл

					// файлы
					$file_list = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_files
						WHERE file_owner = '". $profile_id ."'
					");
					twig::assign('file_list', $file_list);
					// файлы

					// дипломы
					$diplom_list = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_diplom
						WHERE file_owner = '". $profile_id ."'
					");
					twig::assign('diplom_list', $diplom_list);
					// дипломы

					// график работы
					$graph_var = DB::fetchrow("
						SELECT *
						FROM " . DB::$db_prefix . "_users_graph
						WHERE graph_owner = '". $profile_id ."'
					");

					foreach ($graph_var as $row) {
						$row->graph_start = unix_to_date($row->graph_start);
						$row->graph_end = unix_to_date($row->graph_end);

						$row->graph_start_replaced = str_replace([" ", ":", "."], "_", $row->graph_start);
						$row->graph_end_replaced = str_replace([" ", ":", "."], "_", $row->graph_end);

						$city_list = city_by_lang($row->graph_country);

						$row->graph_country = $country_list[$row->graph_country];
						$row->graph_city = $city_list[$row->graph_city];
					}
					twig::assign('graph_var', $graph_var);
					// график работы

					$profile_info->user_children_from = ($profile_info->user_children_from) ? $profile_info->user_children_from : "";
					$profile_info->user_children_to = ($profile_info->user_children_to) ? $profile_info->user_children_to : "";
					$profile_info->user_student_from = ($profile_info->user_student_from) ? $profile_info->user_student_from : "";
					$profile_info->user_student_to = ($profile_info->user_student_to) ? $profile_info->user_student_to : "";

					$profile_info->user_place_work = explode(",", $profile_info->user_place_work);
					$profile_info->user_level_2	= explode(",", $profile_info->user_level_2);
					$profile_info->user_age_2 = explode(",", $profile_info->user_age_2);

					$profile_info->user_work = DB::numrows("
						SELECT order_id FROM " . DB::$db_prefix . "_users_order
						WHERE order_perfomens = '". $profile_id ."' AND order_accept = '1'
					");
					
					//if($serv_list[3]) {
						$place_var = DB::fetchrow("
							SELECT * FROM
								" . DB::$db_prefix . "_users_services_place
							WHERE owner_id = '".$profile_id."'
						");
						foreach ($place_var as $row) {
							$row->place_text = explode(",", $row->place_text);
							$row->city = city_by_lang($row->country_id);
						}
						twig::assign('place_var', $place_var);
					//}

					$tpl = "perfomens_profile_edit";
				break;

				case '4':

					$profile_info->user_work = DB::numrows("
						SELECT order_id FROM " . DB::$db_prefix . "_users_order
						WHERE order_owner = '". $profile_id ."' AND order_status != '0' AND order_delete != '1'
					");

					$tpl = "owner_profile_edit";
				break;

				default:
					$tpl = "other_profile_edit";
				break;
			}

			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			twig::assign('content', twig::fetch('frontend/'.$tpl.'.tpl'));
		}
	}

	static function open_profile() {

		$profile_id = (int)$_REQUEST['profile_id'];
		$profile_info = get_user_info($profile_id);

		if(!$_SESSION['user_id'] && $profile_info->user_group == 4) {
			header("Location: /");
		}

		if(!$profile_info || $profile_info->user_status != 1) {
			site::error404();
		} else {

			// title
			$page_info->page_title = $profile_info->user_name;
			twig::assign('page_info', $page_info);
			// title

			$is_friend = siteFriends::is_friends($_SESSION['user_id'], $profile_id);
			twig::assign('is_friend', $is_friend);

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			$city_list = city_by_lang($profile_info->user_country);
			twig::assign('city_list', $city_list);

			if($profile_info->user_type_form == 2) {
				$company_info = DB::row("
					SELECT * FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$profile_id."'
				");
				twig::assign('company_info', $company_info);
			}

			// альбом
			$album_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_album
				WHERE file_owner = '". $profile_id ."'
			");
			twig::assign('album_list', $album_list);
			// альбом

			// отзывы
			$limit = 5;
			$start = get_current_page() * $limit - $limit;

			$otziv_list = siteOtziv::get_otziv_by_id($profile_id, $limit, $start);
			twig::assign('otziv_list', $otziv_list);

			// pager
			$num = DB::numrows("
				SELECT
					otz.otziv_id
				FROM " . DB::$db_prefix . "_users_otziv as otz
				JOIN " . DB::$db_prefix . "_users_order as ord on ord.order_id = otz.otzive_order
				WHERE otziv_to_id = '". $profile_id ."'
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME. '/profile-'.$profile_id.'/?page={s}">{t}</a>');
				twig::assign('page_nav', $page_nav);
			}
			// pager
			// отзывы

			switch ($profile_info->user_group) {
				case '3':
					$lang_list = get_book_for_essence(1);

					$experience_list = get_book_for_essence(3);
					twig::assign('experience_list', $experience_list);

					// справочник по уровням владения языком
					$lang_level = get_book_for_essence(14);
					twig::assign('lang_level', $lang_level);
					// справочник по уровням владения языком

					// справочник по возрасту обучаемых
					$lang_age = get_book_for_essence(19);
					twig::assign('lang_age', $lang_age);
					// справочник по возрасту обучаемых

					// справочник по Место занятий
					$place_work_list = get_book_for_essence(17);
					twig::assign('place_work_list', $place_work_list);
					// справочник по Место занятий

					// справочник по Перечень оказываемых услуг
					$lang_service = get_book_for_essence(20);
					twig::assign('lang_service', $lang_service);
					// справочник по Перечень оказываемых услуг

					self::profile_info_bilder($profile_id);

					$profile_info->user_default_lang = $lang_list[$profile_info->user_default_lang];

					$profile_info->user_work = DB::numrows("
						SELECT order_id FROM " . DB::$db_prefix . "_users_order
						WHERE order_perfomens = '". $profile_id ."' AND order_accept = '1'
					");

					$tpl = "perfomens_profile_open";
				break;

				case '4':

					self::profile_info_bilder($profile_id);

					$profile_info->user_work = DB::numrows("
						SELECT order_id FROM " . DB::$db_prefix . "_users_order
						WHERE order_owner = '". $profile_id ."' AND order_status != '0' AND order_delete != '1'
					");

					$tpl = "owner_profile_open";
				break;

				default:

					$tpl = "other_profile_open";
				break;
			}

			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/'.$tpl.'.tpl'));
		}
	}

	static function saveProfile() {        

		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		// справочник по языкам
		$user_id = (int)$_SESSION['user_id'];
		$user_info = get_user_info($user_id);
		if(!$user_info) {
			exit;
		}
				
		// загрузка аватара
		if($_REQUEST['uploads_photo']) {
			$photos = "";
			if($_FILES['user_photo'] && image_get_info($_FILES['user_photo']['tmp_name'])) {
				// сохранение фотографии
				$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/"; // адрес директории с изображениями
				$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_orig_dir')."/"; // адрес директории с изображениями
				$photos_tmp = $_FILES['user_photo']['tmp_name']; // изображения для данных
				$photos_name = $_FILES['user_photo']['name']; // изображения для данных
				if(image_get_info($photos_tmp)) {
					$photos = rename_file($photos_name, $targetPath); // переименовываем
				}

				if ($photos != false) {
					$user_photo = DB::single("SELECT user_photo FROM " . DB::$db_prefix . "_users WHERE id = '".$user_id."' ");
					if ($user_photo) {
						@unlink(BASE_DIR . "/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/" . $user_photo);
						@unlink(BASE_DIR . "/".config::app('app_upload_dir')."/".config::app('app_users_orig_dir')."/" . $user_photo);
					}
					$targetFile =  $targetPath . $photos;
					$targetFile2 =  $targetPath2 . $photos;
					move_uploaded_file($photos_tmp, $targetFile);
					copy($targetFile, $targetFile2);

					require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';
					$thumb = new PHPThumb\GD($targetFile);
					$thumb->resize(600, 600);
					$thumb->save($targetFile);

					$thumb = new PHPThumb\GD($targetFile2);
					$thumb->resize(600, 600);
					$thumb->save($targetFile2);

					if($user_id == $_SESSION['user_id']) {
						$_SESSION['user_photo'] = $photos ? $photos : "no-avatar.png";

						DB::query("
							UPDATE
								" . DB::$db_prefix . "_users
							SET
								user_photo = '".$photos."'
							WHERE id = '".$user_id."'
						");

						$photo_small =  ABS_PATH ."?thumb=". config::app("app_upload_dir") . "/".config::app('app_users_dir')."/".$photos."&width=250&height=300";
						$photo_big =  ABS_PATH . config::app("app_upload_dir") . "/".config::app('app_users_orig_dir')."/".$photos;

						echo json_encode(array("respons"=>twig::$lang['lk_photo_upload_success'], "photo_small"=> $photo_small, "photo_big"=>$photo_big,  "status"=>"success"));
					}
				}
			}

			exit;
		}
		// загрузка аватара

		// загрузка логотипа
		if($_REQUEST['company_photo']) {
			$photos = "";
			if($_FILES['company_photo'] && image_get_info($_FILES['company_photo']['tmp_name'])) {
				// сохранение фотографии
				$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_dir')."/"; // адрес директории с изображениями
				$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_orig_dir')."/"; // адрес директории с изображениями
				$photos_tmp = $_FILES['company_photo']['tmp_name']; // изображения для данных
				$photos_name = $_FILES['company_photo']['name']; // изображения для данных
				if(image_get_info($photos_tmp)) {
					$photos = rename_file($photos_name, $targetPath); // переименовываем
				}

				if ($photos != false) {
					$company_photo = DB::single("SELECT company_photo FROM " . DB::$db_prefix . "_users_company WHERE id = '".$user_id."' ");
					if ($company_photo) {
						@unlink($targetPath . $company_photo);
						@unlink($targetPath2 . $company_photo);
					}
					$targetFile =  $targetPath . $photos;
					$targetFile2 =  $targetPath2 . $photos;
					move_uploaded_file($photos_tmp, $targetFile);
					copy($targetFile, $targetFile2);

					require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';
					$thumb = new PHPThumb\GD($targetFile);
					$thumb->resize(600, 600);
					$thumb->save($targetFile);

					$thumb = new PHPThumb\GD($targetFile2);
					$thumb->resize(600, 600);
					$thumb->save($targetFile2);

					if($user_id == $_SESSION['user_id']) {

						$check = DB::single("
							SELECT company_id FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$user_id."'
						");

						if($check) {
							DB::query("
								UPDATE
									" . DB::$db_prefix . "_users_company
								SET
									company_photo = '".$photos."'
								WHERE company_owner = '".$user_id."'
							");
						} else {
							DB::query("
								INSERT INTO
									" . DB::$db_prefix . "_users_company
								SET
									company_photo = '".$photos."',
									company_owner = '".$user_id."'
							");
						}

						DB::query("
							UPDATE
								" . DB::$db_prefix . "_users
							SET
								user_type_form	= '2'
							WHERE id = '".$user_id."'
						");

						$photo_small =  ABS_PATH ."?thumb=". config::app("app_upload_dir") . "/".config::app('app_company_dir')."/".$photos."&width=250&height=300";
						$photo_big =  ABS_PATH . config::app("app_upload_dir") . "/".config::app('app_company_orig_dir')."/".$photos;

						echo json_encode(array("respons"=>twig::$lang['lk_photo_upload_success'], "photo_small"=> $photo_small, "photo_big"=>$photo_big,  "status"=>"success"));
					}
				}
			}

			exit;
		}
		// загрузка логотипа

		$user_login = clear_text($_REQUEST['user_login']);
		$user_firstname = clear_text($_REQUEST['user_firstname']);
		$user_lastname = clear_text($_REQUEST['user_lastname']);
		$user_birthday = date_to_unix($_REQUEST['user_birthday']);
		$user_email = clear_text($_REQUEST['user_email']);
		$user_phone = clear_phone_to_int($_REQUEST['user_phone']);
		$user_skype = clear_text($_REQUEST['user_skype']);
		$user_country = (int)($_REQUEST['user_country']);
		$user_city = (int)($_REQUEST['user_city']);
		$user_default_lang = (int)($_REQUEST['user_default_lang']);
		$user_notice = $_REQUEST['user_notice'];

		$user_password_old = strip_tags($_REQUEST['user_password_old']);
		$user_password_new = strip_tags($_REQUEST['user_password_new']);
		$user_password_new_copy = strip_tags($_REQUEST['user_password_new_copy']);
		$user_type_form = $user_info->user_type_form;

		// исполнители
		$user_vk = clear_text($_REQUEST['user_vk']);
		$user_fb = clear_text($_REQUEST['user_fb']);
		$user_in = clear_text($_REQUEST['user_in']);
		$user_youtube = clear_text($_REQUEST['user_youtube']);
		$user_lang_default = (int)($_REQUEST['user_lang_default']);

		$user_skill = ($user_type_form == 1) ? $_REQUEST['user_skill_1'] : $_REQUEST['user_skill_2'];
		$user_experience_1 = (in_array(1, $user_skill)) ? (int)$_REQUEST['user_experience_1'] : 0;
		$user_experience_2 = (in_array(2, $user_skill)) ? (int)$_REQUEST['user_experience_2'] : 0;
		$user_experience_3 = (in_array(3, $user_skill)) ? (int)$_REQUEST['user_experience_3'] : 0;

		$user_theme = $_REQUEST['user_theme'];
		$user_pays = $_REQUEST['user_pays'];

		$user_level_2 = $_REQUEST['user_level_2'];
		$user_age_2 = $_REQUEST['user_age_2'];
		$user_place_work = $_REQUEST['user_place_work'];
		$user_service_3 = $_REQUEST['user_service_3'];

		$user_children_from = (int)($_REQUEST['user_children_from']);
		$user_children_to = (int)($_REQUEST['user_children_to']);
		$user_student_from = (int)($_REQUEST['user_student_from']);
		$user_student_to = (int)($_REQUEST['user_student_to']);

		$user_vk = str_replace(array("http://vk.com/", "https://vk.com/"), "", $user_vk);
		$user_fb = str_replace(array("http://facebook.com/", "https://facebook.com/", "http://fb.com/", "https://fb.com/"), "", $user_fb);
		$user_in = str_replace(array("http://www.instagram.com/", "https://www.instagram.com/", "http://instagram.com/", "https://instagram.com/"), "", $user_in);
		// исполнители


		// заказчики

		// заказчики

		$error = [];

		if($_REQUEST['password']) {
			
			if(password_verify($user_password_old, $_SESSION['user_password']) === false) {
				$error[] = twig::$lang['lk_password_old_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(empty($user_password_new) || mb_strlen($user_password_new) < config::app('app_min_strlen_password')) {
				$error[] = twig::$lang['lk_password_new_short_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if($user_password_new != $user_password_new_copy) {
				$error[] = twig::$lang['lk_password_new_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if(!preg_match("/^[a-z0-9_]+$/i", $user_password_new)) {
				$error[] = twig::$lang['lk_password_new_charset_error'];
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

		
			$hash = password_hash($user_password_new, PASSWORD_DEFAULT);

			DB::query("
				UPDATE " . DB::$db_prefix . "_users
				SET
					user_password   = '" . $hash . "'
				WHERE
					id = '" . $_SESSION['user_id'] . "'
			");
			

			$user_info = get_user_info();
			auth::set_auth_param($user_info, $user_info->user_password);

			echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
			exit;
		}

		if(mb_strlen($user_login)>20 || mb_strlen($user_login)<config::app('app_min_strlen') || preg_match( "/[^a-zA-Z0-9._-]/iu", $user_login)) {
			$error[] = twig::$lang['profile_login_error'];
		}

		if($user_birthday && $user_birthday>=time()) {
			$error[] = twig::$lang['profile_birthday_error'];
		}

		if($user_skype && preg_match( "/[^a-z0-9._]/iu", $user_skype)) {
			$error[] = twig::$lang['profile_skype_error'];
		}

		$user_notice = ($_REQUEST['user_notice'] == 1) ? 1 : 2;
		$user_notice_var = [];
		foreach ($_REQUEST['user_notice_var'] as $key => $value) {
			$user_notice_var[] = array_key_exists($value, twig::$lang['lk_notice_array'][1]["var"]) ? $value : "";
		}

		if(!$user_notice_var) {
			$user_notice_var = array_keys(twig::$lang['lk_notice_array'][1]["var"]);
		}

		/*if(mb_strlen($user_firstname)<config::app('app_min_strlen')) {
			$error[] = twig::$lang['profile_firstname_error'];
		}*/

		if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
			$error[] = twig::$lang['profile_email_error'];
		} else {

			if($user_email!=$_SESSION['user_email']) {
				$test = DB::row("
					SELECT user_email FROM " . DB::$db_prefix . "_users
					WHERE
						UPPER(user_email) = UPPER('".addslashes($user_email) . "') AND id != '".$_SESSION['user_id']."'
				");
				if($test) {
					$error[] = twig::$lang['profile_email_used_error'];
				}
			}
		}

		foreach ($user_notice as $value) {
			if($value && !array_key_exists($value, twig::$lang['lk_notice_array'])) {
				$error[] = twig::$lang['lk_notice_error'];
			}
		}

		foreach ($user_pays as $value) {
			if(!in_array($value, [1,2,3,4,5])) {
				$error[] = twig::$lang['lk_pays_error'];
			}
		}

		if(!array_key_exists($user_default_lang, $lang_list)) {
			$error[] = twig::$lang['profile_default_lang_error'];
		}


		switch ($_SESSION['user_group']) {
			case '3':

				foreach ($user_skill as $value) {
					if(!array_key_exists($value, twig::$lang['lk_skill_array'])) {
						$error[] = twig::$lang['lk_skill_error'];
					}
				}

				$experience_list = get_book_for_essence(3);
				if($user_experience_1 && ($user_experience_1 < 1900 || $user_experience_1 > date("Y", time()))) {
					$error[] = twig::$lang['lk_experience_error'];
				}
				if($user_experience_2 && ($user_experience_2 < 1900 || $user_experience_2 > date("Y", time()))) {
					$error[] = twig::$lang['lk_experience_error'];
				}
				if($user_experience_3 && ($user_experience_3 < 1900 || $user_experience_3 > date("Y", time()))) {
					$error[] = twig::$lang['lk_experience_error'];
				}

				$theme_list = get_book_for_essence(2);
				foreach ($user_theme as $key => $value) {
					if($value && !array_key_exists($value, $theme_list)) {
						$error[] = twig::$lang['lk_theme_error'];
					}
				}


				$lang_level = get_book_for_essence(14);
				foreach ($user_level_2 as $value) {
					if($value && !array_key_exists($value, $lang_level)) {
						$error[] = twig::$lang['lk_lang_level_error'];
					} else {
						$value = (int)$value;
					}
				}

				$lang_age = get_book_for_essence(19);
				foreach ($user_age_2 as $value) {
					if($value && !array_key_exists($value, $lang_age)) {
						$error[] = twig::$lang['lk_lang_age_error'];
					} else {
						$value = (int)$value;
					}
				}

				$lang_service = get_book_for_essence(20);
				foreach ($user_service_3 as $value) {
					if($value && !array_key_exists($value, $lang_service)) {
						$error[] = twig::$lang['lk_lang_service_error'];
					} else {
						$value = (int)$value;
					}
				}


				$place_work_list = get_book_for_essence(17);
				foreach ($user_place_work as $value) {
					if($value && !array_key_exists($value, $place_work_list)) {
						$error[] = twig::$lang['lk_place_work_error'];
					} else {
						$value = (int)$value;
					}
				}

				if(
					($user_children_from && $user_children_to && $user_children_from>$user_children_to) ||
					($user_student_from && $user_student_to && $user_student_from>$user_student_to)
				) {
					$error[] = twig::$lang['lk_children_student_age_error'];
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
					DELETE FROM
						" . DB::$db_prefix . "_users_graph
					WHERE graph_owner = '".$user_id."'
				");

				foreach ($_REQUEST['graph_start'] as $key => $value) {
					$graph_start = date_to_unix($_REQUEST['graph_start'][$key]);
					$graph_end = date_to_unix($_REQUEST['graph_end'][$key]);

					$graph_country = (int)$_REQUEST['graph_country'][$key];
					$graph_city = (int)$_REQUEST['graph_city'][$key];
					$graph_work = (int)$_REQUEST['graph_work'][$key];

					$check_graph = false;
					$start_check = date("d.m.Y 00:00", time());
					$end_check = date("d.m.Y 23:55", time());
                    if ($graph_work == 0 && $graph_start>=$start_check && $graph_start<=$graph_end) {
						$check_graph = true;
						$graph_country = 0;
						$graph_city = 0;
					}

					if($graph_work == 1 && $graph_start>=$end_check && $graph_start<=$graph_end && $graph_country) {
						$check_graph = true;
					}

					if($check_graph === true) {

						$test_date = DB::row("
							SELECT * FROM
								" . DB::$db_prefix . "_users_graph
							WHERE graph_owner = '".$user_id."' and


                                (
                                    (graph_start <= ".($graph_start)." && graph_end >= ".($graph_start)." && ".($graph_end)." >= graph_end) OR
                                    (graph_start >= ".($graph_start)." && ".($graph_end)." <= graph_end && ".($graph_end)." >= graph_start) OR

                                    (graph_start >= ".($graph_start)." && graph_end <= ".($graph_start)." && ".($graph_end)." <= graph_end && ".($graph_end)." >= graph_start) OR
                                    (graph_start >= ".($graph_start)." && ".($graph_end)." >= graph_end)
                                )

						");

						if(!$test_date) {
							DB::query("
								INSERT INTO
									" . DB::$db_prefix . "_users_graph
								SET
									graph_start = '".$graph_start."',
									graph_end = '".$graph_end."',
									graph_country = '".$graph_country."',
									graph_city = '".$graph_city."',
									graph_work = '".$graph_work."',
									graph_owner = '".$user_id."'
							");
                        }
					}
				}


				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_langvar
					WHERE var_owner = '".$user_id."'
				");


				/*foreach ($_REQUEST['var_lang_from'] as $key => $value) {
					$slice_arr[$_REQUEST['var_lang_from'][$key]."_".$_REQUEST['var_lang_to'][$key]] = [$_REQUEST['var_lang_from'][$key], $_REQUEST['var_lang_to'][$key]];
				}*/

				$slice_arr = [];
				for ($i=0; $i < count($_REQUEST['var_lang_from']); $i++) {
					if(
						!$slice_arr[$_REQUEST['var_lang_from'][$i]."_".$_REQUEST['var_lang_to'][$i]] &&
						!$slice_arr[$_REQUEST['var_lang_to'][$i]."_".$_REQUEST['var_lang_from'][$i]]
					) {
							$slice_arr[$_REQUEST['var_lang_from'][$i]."_".$_REQUEST['var_lang_to'][$i]] = [$_REQUEST['var_lang_from'][$i], $_REQUEST['var_lang_to'][$i]];
					}

				}

				foreach ($slice_arr as $key => $value) {
					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_langvar
						SET
							var_lang_from = '".(int)$value[0]."',
							var_lang_to = '".(int)$value[1]."',
							var_owner = '".$user_id."'
					");
				}

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_skill
					WHERE skill_owner = '".$user_id."'
				");
				foreach ($user_skill as $key => $value) {
					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_skill
						SET
							skill_type = '".(int)$value."',
							skill_owner = '".$user_id."'
					");
				}

				// скилл
				$update_cache = DB::fetchrow("SELECT * FROM " . DB::$db_prefix . "_users_skill WHERE skill_owner = '".$user_id."'", 0);
				// скилл

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_services
					WHERE serv_owner = '".$user_id."'
				");
				// справочник по средствам связи
				$communication_list = get_book_for_essence(4);
				// справочник по средствам связи

				// справочник по опыту работы
				$currency_list = get_book_for_essence(5);
				// справочник по опыту работы

				// справочник по видам услуг
				//$service_list = get_book_for_essence(6);
				// справочник по видам услуг

				// справочник по видам услуг
				//$_list = get_book_for_essence(7);
				// справочник по видам услуг

				// справочник по опыту работы
				$time_list = get_book_for_essence(8);
				// справочник по опыту работы

				// справочник по опыту работы
				$theme_list = get_book_for_essence(2);
				// справочник по опыту работы

				// справочник по опыту работы
				$level_list = get_book_for_essence(14);
				// справочник по опыту работы

				// справочник по опыту работы
				$place_list = get_book_for_essence(17);
				// справочник по опыту работы


				foreach ($_REQUEST['serv_lang_from'] as $key => $value) {
					$book_id = siteOrder::$book_link[(int)$_REQUEST['serv_service'][$key]];
					$service_type_list = get_book_for_essence($book_id);

					if(
						$_REQUEST['serv_lang_from'][$key]!=$_REQUEST['serv_lang_to'][$key] &&
						array_key_exists($_REQUEST['serv_service'][$key], twig::$lang['lk_skill_array']) &&
						array_key_exists($_REQUEST['serv_type_service'][$key], $service_type_list) &&

						floatval($_REQUEST['serv_coast'][$key]) > 0 &&

						array_key_exists($_REQUEST['serv_currency'][$key], $currency_list) &&
						array_key_exists($_REQUEST['serv_time'][$key], $time_list)
					) {


						$check = DB::row("
							SELECT serv_id FROM " . DB::$db_prefix . "_users_services
							WHERE
								serv_owner = '".$user_id."' AND
								serv_lang_from = '".(int)$_REQUEST['serv_lang_from'][$key]."' AND
								serv_lang_to = '".(int)$_REQUEST['serv_lang_to'][$key]."' AND
								serv_service = '".(int)$_REQUEST['serv_service'][$key]."' AND
								serv_place = '".(int)$_REQUEST['serv_place'][$key]."' AND
								serv_type_service = '".(int)$_REQUEST['serv_type_service'][$key]."' AND
								serv_currency = '".(int)$_REQUEST['serv_currency'][$key]."' AND
								serv_time = '".(int)$_REQUEST['serv_time'][$key]."'
						");



						if(!$check) {
							DB::query("
								INSERT INTO
									" . DB::$db_prefix . "_users_services
								SET
									serv_lang_from = '".(int)$_REQUEST['serv_lang_from'][$key]."',
									serv_lang_to = '".(int)$_REQUEST['serv_lang_to'][$key]."',
									serv_service = '".(int)$_REQUEST['serv_service'][$key]."',
									serv_place = '".(int)$_REQUEST['serv_place'][$key]."',
									serv_type_service = '".(int)$_REQUEST['serv_type_service'][$key]."',
									serv_coast = '".format_string_number($_REQUEST['serv_coast'][$key], true)."',
									serv_currency = '".(int)$_REQUEST['serv_currency'][$key]."',
									serv_time = '".(int)$_REQUEST['serv_time'][$key]."',
									serv_owner = '".$user_id."'
							");
						}
					}
				}

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_services_place
					WHERE
						owner_id = '".$user_id."'
				");


				$sql_save = [];

				if(in_array(1, $_REQUEST['user_skill_1']) || in_array(1, $_REQUEST['user_skill_2'])) {
					$sql_save[] = "user_experience_1 = '".$user_experience_1."' ";
					$sql_save[] = "user_theme = '". implode(",", $user_theme) ."' ";
				} else {
					DB::query("
						DELETE FROM
							" . DB::$db_prefix . "_users_services
						WHERE serv_owner = '".$user_id."' AND serv_service = '1'
					");

					$sql_save[] = "user_experience_1 = '' ";
					$sql_save[] = "user_theme = '' ";
				}

				if(in_array(2, $_REQUEST['user_skill_1']) || in_array(2, $_REQUEST['user_skill_2'])) {
					$sql_save[] = "user_experience_2 = '".$user_experience_2."' ";
					$sql_save[] = "user_place_work = '". implode(",", $user_place_work) ."' ";
					$sql_save[] = "user_level_2 = '". implode(",", $user_level_2) ."' ";
					$sql_save[] = "user_age_2 = '". implode(",", $user_age_2) ."' ";

				} else {
					$sql_save[] = "user_experience_2 = '' ";
					$sql_save[] = "user_place_work = '' ";
					$sql_save[] = "user_level_2 = '' ";
					$sql_save[] = "user_age_2 = '' ";

					DB::query("
						DELETE FROM
							" . DB::$db_prefix . "_users_services
						WHERE serv_owner = '".$user_id."' AND serv_service = '2'
					");
				}

				if(in_array(3, $_REQUEST['user_skill_1']) || in_array(3, $_REQUEST['user_skill_2'])) {
					$sql_save[] = "user_experience_3 = '".$user_experience_3."' ";
					$sql_save[] = "user_service_3 = '". implode(",", $user_service_3) ."' ";

					foreach ($_REQUEST['user_country_place'] as $key => $value) {

						$place_text = [];
						$str_len = 0;
						foreach ($_REQUEST['place_text'][$key] as $text) {
							if(!empty(clear_text($text))) $str_len++;
							$place_text[] = clear_text($text);
						}

						if($str_len >= 1) {
							DB::query("
								INSERT INTO
									" . DB::$db_prefix . "_users_services_place
								SET
									country_id = '".(int)$_REQUEST['user_country_place'][$key]."',
									city_id = '".(int)$_REQUEST['user_city_place'][$key]."',
									place_text = '".implode(",",$place_text)."',
									owner_id = '".$user_id."'
							");
						}
					}
				} else {
					$sql_save[] = "user_experience_3 = '' ";
					$sql_save[] = "user_service_3 = '' ";

					DB::query("
						DELETE FROM
							" . DB::$db_prefix . "_users_services
						WHERE serv_owner = '".$user_id."' AND serv_service = '3'
					");
				}

				$sql_save = implode(",", $sql_save);
				$sql_save = $sql_save ? $sql_save."," : "";

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users
					SET
						$sql_save

						user_default_lang = '".$user_default_lang."',
						user_timezone = '".clear_text($_REQUEST['user_timezone'])."',

						user_login = '".$user_login."',
						user_firstname = '".$user_firstname."',
						user_lastname = '".$user_lastname."',
						user_birthday = '".$user_birthday."',
						user_email = '".$user_email."',
						user_phone = '".$user_phone."',
						user_skype = '".$user_skype."',
						user_country = '".$user_country."',
						user_city = '".$user_city."',

						user_in_work = '".($_REQUEST['user_in_work'] == 1 ? 1 : 0)."',
						user_notice = '".$user_notice."',
						user_skill = '".implode(",", $user_skill)."',
						user_notice_var = '".implode(",",$user_notice_var)."',
						user_pays = '".implode(",",$user_pays)."'

					WHERE id = '".$user_id."'
				");

				$user_info = get_user_info();
				auth::set_auth_param($user_info, $user_info->user_password);
			break;

			case '4':


				if(clear_text($_REQUEST['company_email']) && !filter_var(clear_text($_REQUEST['company_email']), FILTER_VALIDATE_EMAIL)) {
					$error[] = twig::$lang['profile_email_error'];
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
					UPDATE
						" . DB::$db_prefix . "_users
					SET
						user_login = '".$user_login."',
						user_firstname = '".$user_firstname."',
						user_lastname = '".$user_lastname."',
						user_birthday = '".$user_birthday."',
						user_default_lang = '".$user_default_lang."',
						user_timezone = '".clear_text($_REQUEST['user_timezone'])."',
						user_email = '".$user_email."',
						user_phone = '".$user_phone."',
						user_skype = '".$user_skype."',
						user_country = '".$user_country."',
						user_city = '".$user_city."',
						user_notice = '".$user_notice."',
						user_notice_var = '".implode(",",$user_notice_var)."',
						user_pays = '".implode(",",$user_pays)."'
					WHERE id = '".$user_id."'
				");

				$user_info = get_user_info();
				auth::set_auth_param($user_info, $user_info->user_password);
			break;
		}


		// удаление файлов
		if($_REQUEST['delete_file_attach']) {
			$files_attach = [];
			foreach($_REQUEST['delete_file_attach'] as $row) {
				$files_attach[] = (int)$row;
			}

			$file_list = DB::fetchrow("
				SELECT * FROM 
					" . DB::$db_prefix . "_users_album
				WHERE file_owner = '". $user_id ."' AND file_id IN (".implode(",", $files_attach).")
			");

			$path = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_album_dir')."/".$user_id."/";
			foreach($file_list as $file) {
				unlink($path.$file->file_path);
			}

			DB::fetchrow("
				DELETE FROM
					" . DB::$db_prefix . "_users_album
				WHERE file_owner = '". $user_id ."' AND file_id IN (".implode(",", $files_attach).")
			");
		}
		// удаление файлов
		

		if($user_type_form == 2) {
			$check = DB::single("
				SELECT company_id FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$_SESSION['user_id']."'
			");

			$company_site = clear_text($_REQUEST['company_site']);
			$company_site = str_replace(["http://", "https://", "http://www.", "https://www.", "www."], "", $company_site);

			if($check) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_company
					SET
						company_title = '".clear_text($_REQUEST['company_title'])."',
						company_type = '".clear_text($_REQUEST['company_type'])."',
						company_desc = '".clear_text($_REQUEST['company_desc'])."',
						company_address = '".clear_text($_REQUEST['company_address'])."',
						company_group = '".clear_text($_REQUEST['company_group'])."',
						company_email = '".clear_text($_REQUEST['company_email'])."',
						company_phone = '".clear_phone_to_int($_REQUEST['company_phone'])."',
						company_site = '".$company_site."'
					WHERE company_owner = '".$_SESSION['user_id']."'
				");
			} else {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_company
					SET
						company_title = '".clear_text($_REQUEST['company_title'])."',
						company_type = '".clear_text($_REQUEST['company_type'])."',
						company_desc = '".clear_text($_REQUEST['company_desc'])."',
						company_address = '".clear_text($_REQUEST['company_address'])."',
						company_group = '".clear_text($_REQUEST['company_group'])."',
						company_email = '".clear_text($_REQUEST['company_email'])."',
						company_phone = '".clear_phone_to_int($_REQUEST['company_phone'])."',
						company_site = '".clear_text($_REQUEST['company_site'])."',
						company_owner = '".$_SESSION['user_id']."'
				");
			}

			if($_FILES['company_photo'] && image_get_info($_FILES['company_photo']['tmp_name'])) {
				// сохранение фотографии
				$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_dir')."/"; // адрес директории с изображениями
				$photos_tmp = $_FILES['company_photo']['tmp_name']; // изображения для данных
				$photos_name = $_FILES['company_photo']['name']; // изображения для данных
				if(image_get_info($photos_tmp)) {
					$photos = rename_file($photos_name, $targetPath); // переименовываем
				}

				if ($photos != false) {
					$company_photo = DB::single("SELECT company_photo FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$_SESSION['user_id']."' ");
					if ($company_photo) @unlink($targetPath . $company_photo);
					$targetFile =  $targetPath . $photos;
					move_uploaded_file($photos_tmp, $targetFile);

					DB::query("
						UPDATE
							" . DB::$db_prefix . "_users_company
						SET
							company_photo = '".$photos."'
						WHERE company_owner = '".$_SESSION['user_id']."'
					");
				}
			}

		} else {
			$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_dir')."/"; // адрес директории с изображениями
			$company_photo = DB::single("SELECT company_photo FROM " . DB::$db_prefix . "_users_company WHERE company_owner = '".$_SESSION['user_id']."' ");
			DB::query("
				DELETE FROM
					" . DB::$db_prefix . "_users_company
				WHERE company_owner = '".$_SESSION['user_id']."'
			");
			if ($company_photo) @unlink($targetPath . $company_photo);
		}

		logs::add(twig::$lang["profile_edit_log"] . ' (' . $user_id . ')', profile::$log_setting);
		header('Location:'.HOST_NAME.'/profile/');
		exit;
	}

	static function load_city_by_country() {
		$country_id = (int)$_REQUEST['country_id'];

		if($country_id) {
			// city
			$city_list = city_by_lang($country_id);

			$html = "";
			foreach ($city_list as $value) {
				$html .= "<option value='".$value['id']."' data-utm='".$value['utm']."'>".$value['title']."</option>";
			}

			echo json_encode(array("respons"=>$html, "status"=>"success"));
			// city
		}
		exit;
	}

	static function load_metro_by_city() {
		$city_id = (int)$_REQUEST['city_id'];

		if($city_id) {
			// city
			$metro_list = metro_by_lang($city_id);

			foreach ($metro_list as $key => $value) {
				if($value['region']) {
					$metro_list[$key]['title'] = $value['title'] . " (".$value['region'].")";
				}
			}

			echo json_encode(array("respons"=>$metro_list, "status"=>"success"));
			// city
		}
		exit;
	}

	static function user_profile_upload() {

		$user_id = (int)$_SESSION['user_id'];
		$user_info = get_user_info($user_id);
		if(!$user_info) {
			exit;
		}

		$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/document/".$_SESSION['user_id']."/";
	    if(!file_exists($targetPath)) {
			@mkdir($targetPath, 0777);
		}

		if (!empty($_FILES['file_path']['name'])) {
	        $file_data = $_FILES['file_path']['name']; // файл основа
	        $file_tmp = $_FILES['file_path']['tmp_name']; // изображения для данных
	        $file_data = rename_file($file_data, $targetPath); // переименовываем

	        $use_original_name = explode(".", $_FILES['file_path']['name']);
	        array_pop($use_original_name);
	        $use_original_name = implode(".", $use_original_name);

			$file_type = file_get_info($file_data);

	        if(mb_strlen($use_original_name) < \config::app('app_min_strlen')) {
				$use_new_name = explode(".", $file_data);
	        	$use_original_name = $use_new_name[0];
	        }

	        if ($file_data != false) {
	            $targetFile =  $targetPath . $file_data;
	            if(move_uploaded_file($file_tmp, $targetFile)) {
	            	DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_files
						SET
							file_path = '".$file_data."',
							file_owner = '".(int)$_SESSION['user_id']."',
							file_date = '".time()."',
							file_type = '".$file_type['mime_type']."',
							file_name = '".clear_text($use_original_name)."'
					");
	            }
		  	}
		  	echo '{"status":"success"}';
	    } else {
	    	echo '{"status":"error"}';
	    }
	    exit;
	}

	static function user_profile_upload_remove() {
		$file_id = (int)$_REQUEST['file_id'];


		$file_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_files
			WHERE file_id = '".$file_id."' AND file_owner = '".$_SESSION['user_id']."'
		");

		if($file_info) {
			$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/document/".$_SESSION['user_id']."/";
			@unlink($targetPath . $file_info->file_path);
			DB::query("
				DELETE FROM
					" . DB::$db_prefix . "_users_files
				WHERE file_id = '".$file_id."' AND file_owner = '".$_SESSION['user_id']."'
			");
		}

		echo json_encode(array("respons"=>twig::$lang["form_save_success"], "status"=>"success"));
		exit;
	}

	static function user_profile_diplom_upload() {

		$user_id = (int)$_SESSION['user_id'];
		$user_info = get_user_info($user_id);
		if(!$user_info) {
			exit;
		}

		$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/diplom/".$_SESSION['user_id']."/";
	    if(!file_exists($targetPath)) {
			@mkdir($targetPath, 0777);
		}

		if (!empty($_FILES['file_path']['name'])) {
	        $file_data = $_FILES['file_path']['name']; // файл основа
	        $file_tmp = $_FILES['file_path']['tmp_name']; // изображения для данных
	        $file_data = rename_file($file_data, $targetPath); // переименовываем

	        $use_original_name = explode(".", $_FILES['file_path']['name']);
	        array_pop($use_original_name);
	        $use_original_name = implode(".", $use_original_name);

	        if(mb_strlen($use_original_name) < \config::app('app_min_strlen')) {
				$use_new_name = explode(".", $file_data);
	        	$use_original_name = $use_new_name[0];
	        }

	        if ($file_data != false) {
	            $targetFile =  $targetPath . $file_data;
	            if(move_uploaded_file($file_tmp, $targetFile)) {
	            	DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_diplom
						SET
							file_path = '".$file_data."',
							file_owner = '".(int)$_SESSION['user_id']."',
							file_date = '".time()."',
							file_name = '".clear_text($use_original_name)."'
					");
	            }
		  	}
		  	echo '{"status":"success"}';
	    } else {
	    	echo '{"status":"error"}';
	    }
	    exit;
	}

	static function user_profile_diplom_upload_remove() {
		$file_id = (int)$_REQUEST['file_id'];

		$file_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_diplom
			WHERE file_id = '".$file_id."' AND file_owner = '".$_SESSION['user_id']."'
		");

		if($file_info) {
			$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/diplom/".$_SESSION['user_id']."/";
			@unlink($targetPath . $file_info->file_path);
			DB::query("
				DELETE FROM
					" . DB::$db_prefix . "_users_diplom
				WHERE file_id = '".$file_id."' AND file_owner = '".$_SESSION['user_id']."'
			");
		}

		echo json_encode(array("respons"=>twig::$lang["form_save_success"], "status"=>"success"));
		exit;
	}

	static function user_exit() {
		session::close_session();

		header('Location:'.HOST_NAME);
		exit;
	}

	static function profile_info_bilder($profile_id) {
		// справочник по языкам
		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);
		// справочник по языкам

		// языковые пары
	/*	$lang_var = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_langvar
			WHERE var_owner = '". $profile_id ."'
		");

		foreach ($lang_var as $row) {
			$row->var_lang_from = $lang_list[$row->var_lang_from];
			$row->var_lang_to = $lang_list[$row->var_lang_to];
		}
		twig::assign('lang_var', $lang_var);*/
		// языковые пары

		// места работы
		$work_list = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_work
			WHERE work_owner = '". $profile_id ."'
		");
		foreach ($work_list as $key => $value) {

			$city_list = city_by_lang($value->work_country);
			$value->city_list = $city_list;
			$value->work_stage = $value->work_year_end-$value->work_year_start;
		}
		twig::assign('work_list', $work_list);
		// места работы

		// справочник по темам
		$theme_list = get_book_for_essence(2);
		twig::assign('theme_list', $theme_list);
		// справочник по темам

		// справочник по опыту работы
		$experience_list = get_book_for_essence(3);
		twig::assign('experience_list', $experience_list);
		// справочник по опыту работы

		// справочник по средствам связи
		$communication_list = get_book_for_essence(4);
		twig::assign('communication_list', $communication_list);
		// справочник по средствам связи

		// справочник по опыту работы
		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);
		// справочник по опыту работы

		// справочник по видам услуг
		$service_list = get_book_for_essence(6);
		twig::assign('service_list', $service_list);
		// справочник по видам услуг

		// справочник по видам услуг
		$service_type_list = get_book_for_essence(7);
		twig::assign('service_type_list', $service_type_list);
		// справочник по видам услуг

		// справочник по опыту работы
		$time_list = get_book_for_essence(8);
		twig::assign('time_list', $time_list);
		// справочник по опыту работы

		// услуги
		/*$serv_list = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_services
			WHERE serv_owner = '". $profile_id ."'
		");
		foreach ($serv_list as $row) {
			//$book_id = siteOrder::$book_link[$row->serv_service];
			//$row->serv_service = get_book_for_essence($book_id);

			$row->serv_lang_from = $lang_list[$row->serv_lang_from];
			$row->serv_lang_to = $lang_list[$row->serv_lang_to];
			$row->serv_communication = $communication_list[$row->serv_communication];
			$row->serv_theme = $theme_list[$row->serv_theme];

			$row->serv_currency = $currency_list[$row->serv_currency];
			$row->serv_type_service = $service_type_list[$row->serv_type_service];
			$row->serv_time = $time_list[$row->serv_time];
			$row->serv_coast = format_string_number($row->serv_coast);
		}
		twig::assign('serv_list', $serv_list);*/
		// услуги

		// языковые пары
		$sql = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_services
			WHERE serv_owner = '". $profile_id ."'
		");
		$serv_lang = [];
		foreach ($sql as $row) {
			$str = $row->serv_lang_from."_".$row->serv_lang_to;
			$row->serv_lang_from = $lang_list[$row->serv_lang_from];
			$row->serv_lang_to = $lang_list[$row->serv_lang_to];

			$serv_lang[$str] = $row;
		}
		twig::assign('serv_lang', $serv_lang);
		// языковые пары

		// услуги
		$sql = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_services
			WHERE serv_owner = '". $profile_id ."'
		");
		$serv_list = [];
		$serv_test = 0;
		foreach ($sql as $row) {

			$book_id = siteOrder::$book_link[$row->serv_service];
			$service_type_list = get_book_for_essence($book_id);

			$row->serv_lang_from = $lang_list[$row->serv_lang_from];
			$row->serv_lang_to = $lang_list[$row->serv_lang_to];
			$row->serv_communication = $communication_list[$row->serv_communication];
			$row->serv_place = $place_work_list[$row->serv_place];
			$row->serv_theme = $theme_list[$row->serv_theme];
			$row->serv_level = $level_list[$row->serv_level];
			$row->serv_currency = $currency_list[$row->serv_currency];
			$row->serv_type_service = $service_type_list[$row->serv_type_service];
			$row->serv_time = $time_list[$row->serv_time];
			$row->serv_coast = format_string_number($row->serv_coast);

			if($row->serv_service == 3) $serv_test = 1;
			$serv_list[] = $row;
		}
		twig::assign('serv_list', $serv_list);
		// услуги

		//if($serv_test == 1) {
			$place_var = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_services_place
				WHERE owner_id = '".$profile_id."'
			");
			foreach ($place_var as $row) {
				$row->place_text = explode(",", $row->place_text);
				$row->city = city_by_lang($row->country_id);
			}
			twig::assign('place_var', $place_var);
		//}

		// скилл
		$sql = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_users_skill
			WHERE skill_owner = '". $profile_id ."'
		");
		$skill_list = [];
		foreach ($sql as $row) {
			$skill_list[$row->skill_type] = $row;
		}
		twig::assign('skill_list', $skill_list);
		// скилл

		$employment_list = get_book_for_essence(10);
		twig::assign('employment_list', $employment_list);

		$time_work_list = get_book_for_essence(11);
		twig::assign('time_work_list', $time_work_list);

		$ready_to_move_list = get_book_for_essence(12);
		twig::assign('ready_to_move_list', $ready_to_move_list);

		$interview_method_list = get_book_for_essence(13);
		twig::assign('interview_method_list', $interview_method_list);

		$resume = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $profile_id ."'
		");
		$resume = siteResume::resume_info($resume);
		$resume->resume_city = city_by_lang($resume->resume_user_country);
		twig::assign('resume', $resume);

		// country
		$country_list = country_by_lang();
		twig::assign('country_list', $country_list);
		// country
	}

	static function load_service_in_profile() {
		$book_id = siteOrder::$book_link[(int)$_REQUEST['book_id']];
		$book_list = get_book_for_essence($book_id);

		echo json_encode(array("respons"=>$book_list, "status"=>"success"));
		exit;
	}

	static function cropPhoto() {
		switch ($_REQUEST['crop_photo']) {
			case '1': // аватар
				if($_REQUEST['x2']-$_REQUEST['x1']>=150 && $_REQUEST['y2']-$_REQUEST['y1']>=150 && $_SESSION['user_photo']!='no-avatar.png') {

					$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_dir')."/"; // адрес директории с изображениями
					$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_users_orig_dir')."/"; // адрес директории с изображениями
					require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';

					$fileData = $targetPath.$_SESSION['user_photo'];
					$fileData2 = $targetPath2.$_SESSION['user_photo'];

					$thumb = new PHPThumb\GD($fileData2);
					$thumb->crop($_REQUEST['x1'], $_REQUEST['y1'], $_REQUEST['x2']-$_REQUEST['x1'], $_REQUEST['y2']-$_REQUEST['y1']);

					$thumb->save($fileData);

					$_REQUEST['thumb'] = $targetPath.$_SESSION['user_photo'];

					thumb::bild_thumb(50, 50, 1);
					thumb::bild_thumb(100, 100, 1);
					thumb::bild_thumb(150, 150, 1);
					thumb::bild_thumb(150, 200, 1);
					thumb::bild_thumb(200, 200, 1);
					thumb::bild_thumb(250, 250, 1);
					thumb::bild_thumb(250, 300, 1);
				}
			break;
			case '2': // лого компании
				if($_REQUEST['x2']-$_REQUEST['x1']>=150 && $_REQUEST['y2']-$_REQUEST['y1']>=150 && $_SESSION['user_photo']!='no-avatar.png') {

					$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_dir')."/"; // адрес директории с изображениями
					$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_company_orig_dir')."/"; // адрес директории с изображениями
					require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';

					$photo = DB::single("
						SELECT company_photo FROM
							" . DB::$db_prefix . "_users_company
						WHERE company_owner = '".$_SESSION['user_id']."'
					");

					$fileData = $targetPath.$photo;
					$fileData2 = $targetPath2.$photo;

					$thumb = new PHPThumb\GD($fileData2);
					$thumb->crop($_REQUEST['x1'], $_REQUEST['y1'], $_REQUEST['x2']-$_REQUEST['x1'], $_REQUEST['y2']-$_REQUEST['y1']);

					$thumb->save($fileData);

					$_REQUEST['thumb'] = $targetPath.$photo;

					thumb::bild_thumb(50, 50, 1);
					thumb::bild_thumb(100, 100, 1);
					thumb::bild_thumb(150, 150, 1);
					thumb::bild_thumb(150, 200, 1);
					thumb::bild_thumb(200, 200, 1);
					thumb::bild_thumb(250, 250, 1);
					thumb::bild_thumb(250, 300, 1);
				}
			break;
		}

		header('Location:'.HOST_NAME.'/profile/');
		exit;
	}

	static function add_file_to_album() {

		if(!$_SESSION['user_id']) {
			access_404();
		}

		$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_album_dir') . "/" . $_SESSION['user_id'] . "/";
	    if(!file_exists($targetPath)) {
			@mkdir($targetPath, 0777);
		}

		$respons = ["isSuccess" => false];
		if (!empty($_FILES['file_path']['name'][0]) && image_get_info($_FILES['file_path']['tmp_name'][0])) {
	        $file_data = $_FILES['file_path']['name'][0]; // файл основа
	        $file_tmp = $_FILES['file_path']['tmp_name'][0]; // изображения для данных
	        $file_data = rename_file($file_data, $targetPath); // переименовываем

	        $use_original_name = explode(".", $_FILES['file_path']['name'][0]);
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
							" . \DB::$db_prefix . "_users_album
						SET
							file_owner = '".$_SESSION['user_id']."',
							file_path = '".$file_data."',
							file_date = '".time()."',
							file_name = '".clear_text($use_original_name)."'
					");
					$file_id = \DB::lastInsertId();

					$respons = [
						"hasWarnings" => false,
						"isSuccess" => true,
						"warnings" => [],
						"files" => [
							[
								"date" => date(DATE_RFC822, time()),
								"editor" => false,
								"extension" => array_pop(explode(".", $_FILES['file_path']['name'][0])),
								"name" => clear_text($use_original_name).".".array_pop(explode(".", $_FILES['file_path']['name'][0])),
								"old_name" => clear_text($use_original_name).".".array_pop(explode(".", $_FILES['file_path']['name'][0])),
								"title" => clear_text($use_original_name),
								"old_title" => clear_text($use_original_name),
								"file" => ABS_PATH . "/".config::app('app_upload_dir')."/".config::app('app_album_dir') . "/" . $_SESSION['user_id'] . "/" . $file_data,
								"uploaded" => true,
								"replaced" => false,
								"size" => filesize($targetFile),
								"type" => mime_content_type($targetFile),
							]
						]
					];
				}
		  	}
	    }

		echo json_encode($respons);
	    exit;
	}

	

}

?>
