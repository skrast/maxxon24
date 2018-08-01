<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteResume {

	static function resume() {
		// title
		$page_info->page_title = twig::$lang['resume_title'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 3) {
			site::error404();
		} else {

			$resume_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume
				WHERE resume_owner = '". $_SESSION['user_id'] ."'
			");
			$resume_info->resume_birthday = date("d.m.Y", $resume_info->resume_birthday);
			$city_list = city_by_lang($resume_info->resume_country);
			twig::assign('city_list', $city_list);

			$resume_info = self::resume_info($resume_info);

			twig::assign('resume_info', $resume_info);

			if($_REQUEST['crop_photo']) {
				self::cropPhoto();
			}

			if($_REQUEST['save']) {
				self::saveResume();
			}

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			$lang_list = get_book_for_essence(1);
			$lang_level_list = get_book_for_essence(14);
			twig::assign('lang_list', $lang_list);
			twig::assign('lang_level_list', $lang_level_list);

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

			$jobs_title = get_book_for_essence(21);
			twig::assign('jobs_title', $jobs_title);

			$jobs_edu = get_book_for_essence(22);
			twig::assign('jobs_edu', $jobs_edu);

			$terms_employment = get_book_for_essence(23);
			twig::assign('terms_employment', $terms_employment);

			$work_place = get_book_for_essence(24);
			twig::assign('work_place', $work_place);

			$employment_conditions = get_book_for_essence(10);
			twig::assign('employment_conditions', $employment_conditions);

			$work_time = get_book_for_essence(11);
			twig::assign('work_time', $work_time);
			

			$profile_info->user_default_lang = $lang_list[$profile_info->user_default_lang];

			$profile_info->user_work = DB::numrows("
				SELECT order_id FROM " . DB::$db_prefix . "_users_order
				WHERE order_perfomens = '". $profile_id ."' AND order_accept = '1'
			");

			siteProfile::profile_info_bilder($profile_id);

			$profile_info->user_work = DB::numrows("
				SELECT order_id FROM " . DB::$db_prefix . "_users_order
				WHERE order_perfomens = '". $profile_id ."' AND order_accept = '1'
			");

			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);


			$app_allow_ext = array_keys(\config::app('app_allow_ext'));
			$app_allow_ext = implode(", ", $app_allow_ext);
			$app_allow_ext = str_replace("|", ", ", $app_allow_ext);
			\twig::assign('app_allow_ext', $app_allow_ext);

			$app_allow_img = config::app('app_allow_img');
			twig::assign('app_allow_img', $app_allow_img);

			// альбом
			$album_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume_album
				WHERE file_owner = '". $profile_id ."'
			");
			twig::assign('album_list', $album_list);
			// альбом

			$resume_skill_lang = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume_lang
				WHERE resume_id = '". $profile_id ."'
			");
			twig::assign('resume_skill_lang', $resume_skill_lang);

			$resume_edu = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_edu
				WHERE
					resume_edu_owner = '". $profile_id ."'
			");
			foreach ($resume_edu as $key => $value) {
				$value->city_list = city_by_lang($value->resume_edu_country);
			}
			twig::assign('resume_edu', $resume_edu);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/perfomens_resume_edit.tpl'));
		}
	}

	static function saveResume() {
		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 3) {
			site::error404();
		}

		// загрузка аватара
		if($_REQUEST['uploads_photo']) {
			$photos = "";
			if($_FILES['user_photo'] && image_get_info($_FILES['user_photo']['tmp_name'])) {
				// сохранение фотографии
				$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_resume_dir')."/"; // адрес директории с изображениями
				$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_resume_orig_dir')."/"; // адрес директории с изображениями
				$photos_tmp = $_FILES['user_photo']['tmp_name']; // изображения для данных
				$photos_name = $_FILES['user_photo']['name']; // изображения для данных
				if(image_get_info($photos_tmp)) {
					$photos = rename_file($photos_name, $targetPath); // переименовываем
				}

				if ($photos != false) {
					$user_photo = DB::single("SELECT resume_photo FROM " . DB::$db_prefix . "_users_resume WHERE id = '".$profile_id."' ");
					if ($user_photo) {
						@unlink($targetPath . $user_photo);
						@unlink($targetPath2 . $user_photo);
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

					DB::query("
						UPDATE
							" . DB::$db_prefix . "_users_resume
						SET
							resume_photo = '".$photos."'
						WHERE resume_owner = '".$profile_id."'
					");

					$photo_small =  ABS_PATH ."?thumb=". config::app("app_upload_dir") . "/".config::app('app_resume_dir')."/".$photos."&width=250&height=300";
					$photo_big =  ABS_PATH . config::app("app_upload_dir") . "/".config::app('app_resume_orig_dir')."/".$photos;

					echo json_encode(array("respons"=>twig::$lang['lk_photo_upload_success'], "photo_small"=> $photo_small, "photo_big"=>$photo_big,  "status"=>"success"));
				}
			}

			exit;
		}
		// загрузка аватара

		$error = [];

		$resume = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $_SESSION['user_id'] ."'
		");

		if($_REQUEST['resume_money_none'] == 1) {
			$_REQUEST['resume_money_start'] = "";
			$_REQUEST['resume_money_end'] = "";
			$_REQUEST['resume_money_currency'] = "";
		}
		if($_REQUEST['resume_ready_to_move'] == 40) {
			$_REQUEST['user_country'] = "0";
			$_REQUEST['user_city'] = "0";
		}

		try {
			v::length(config::app('app_min_strlen'), null)->assert(clear_text($_REQUEST['resume_firstname']));
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["profile_firstname_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}
		try {
			v::length(config::app('app_min_strlen'), null)->assert(clear_text($_REQUEST['resume_lastname']));
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["profile_lastname_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}
		if(!filter_var($_REQUEST['resume_email'], FILTER_VALIDATE_EMAIL)) {
			$error[] = twig::$lang['profile_email_error'];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}



		$resume_site = $_REQUEST['resume_site'];
		$count = 0;
		$save_resume_site = [];
		foreach ($resume_site as $key => $value) {
			if($value) {
				try {
					v::url()->assert($value);
					$save_resume_site[] = clear_text($value);
				} catch(ValidationException $exception) {
					$count++;
				}
			}
		}
		$save_resume_site = array_unique($save_resume_site);
		if($count>0) {
			$error[] = twig::$lang["work_site_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}


		foreach ($_REQUEST['resume_edu_title'] as $key => $value) {
			switch ($value) {
				case '93':
				case '94':
				case '95':
				case '97':
					if(
						empty($_REQUEST['resume_edu_univer'][$key]) ||
						empty($_REQUEST['resume_edu_univer_faq'][$key]) ||
						empty($_REQUEST['resume_edu_univer_spec'][$key]) ||
						empty($_REQUEST['resume_edu_country'][$key]) ||
						empty($_REQUEST['resume_edu_city'][$key]) ||
						empty($_REQUEST['resume_edu_year'][$key])
					) {
						$error[] = twig::$lang["work_edu_error"];

						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}
				break;

				case '98':
					if(
						empty($_REQUEST['resume_edu_univer'][$key]) ||
						empty($_REQUEST['resume_edu_country'][$key]) ||
						empty($_REQUEST['resume_edu_city'][$key]) ||
						empty($_REQUEST['resume_edu_year'][$key])
					) {
						$error[] = twig::$lang["work_edu_error"];

						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}
				break;

				case '96':
				case '99':
					if(
						empty($_REQUEST['resume_edu_org'][$key]) ||
						empty($_REQUEST['resume_edu_curs'][$key]) ||
						empty($_REQUEST['resume_edu_univer_spec'][$key]) ||
						empty($_REQUEST['resume_edu_country'][$key]) ||
						empty($_REQUEST['resume_edu_city'][$key]) ||
						empty($_REQUEST['resume_edu_year'][$key])
					) {
						$error[] = twig::$lang["work_edu_error"];

						$html = twig::fetch('chank/error_show.tpl');
						echo json_encode(array("respons"=>$html, "status"=>"error"));
						exit;
					}
				break;
			}
		}

		/*if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}*/

		if($resume) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_resume
				SET
					resume_lastname = '". clear_text($_REQUEST['resume_lastname'])."',
					resume_firstname = '". clear_text($_REQUEST['resume_firstname'])."',
					resume_patronymic = '". clear_text($_REQUEST['resume_patronymic'])."',
					resume_birthday = '". date_to_unix($_REQUEST['resume_birthday'])."',
					resume_country = '". (int)($_REQUEST['resume_country'])."',
					resume_city = '". (int)($_REQUEST['resume_city'])."',
					resume_email = '". clear_text($_REQUEST['resume_email'])."',
					resume_phone = '". clear_text($_REQUEST['resume_phone'])."',
					resume_citizenship = '". (int)($_REQUEST['resume_citizenship'])."',

					resume_coast_start = '". format_string_number($_REQUEST['resume_coast_start'], true)."',
					resume_coast_end = '". format_string_number($_REQUEST['resume_coast_end'], true)."',
					resume_coast_currency = '". (int)$_REQUEST['resume_coast_currency']."',
					resume_coast_period = '".($_REQUEST['resume_coast_period'] == 1 ? 1 : 2)."',

					resume_title = '". (int)$_REQUEST['resume_title']."',
					
					resume_ready_to_move = '". (int)$_REQUEST['resume_ready_to_move']."',
					resume_interview_method	 = '". addslashes(serialize($_REQUEST['resume_interview_method']))."',

					resume_terms_employment	 = '". addslashes(serialize($_REQUEST['resume_terms_employment']))."',
					resume_work_place	 = '". addslashes(serialize($_REQUEST['resume_work_place']))."',
					resume_employment_conditions	 = '". addslashes(serialize($_REQUEST['resume_employment_conditions']))."',
					resume_work_time	 = '". addslashes(serialize($_REQUEST['resume_work_time']))."',

					resume_dop_info_desc	 = '". clear_text($_REQUEST['resume_dop_info_desc'])."',
					resume_dop_info_passport	 = '". (int)($_REQUEST['resume_dop_info_passport'])."',

					resume_update = '".time()."'

				WHERE resume_owner = '". $_SESSION['user_id'] ."'
			");
		} else {
			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_resume
				SET
					resume_lastname = '". clear_text($_REQUEST['resume_lastname'])."',
					resume_firstname = '". clear_text($_REQUEST['resume_firstname'])."',
					resume_patronymic = '". clear_text($_REQUEST['resume_patronymic'])."',
					resume_birthday = '". date_to_unix($_REQUEST['resume_birthday'])."',
					resume_country = '". (int)($_REQUEST['resume_country'])."',
					resume_city = '". (int)($_REQUEST['resume_city'])."',
					resume_email = '". clear_text($_REQUEST['resume_email'])."',
					resume_phone = '". clear_text($_REQUEST['resume_phone'])."',
					resume_citizenship = '". (int)($_REQUEST['resume_citizenship'])."',

					resume_coast_start = '". format_string_number($_REQUEST['resume_coast_start'], true)."',
					resume_coast_end = '". format_string_number($_REQUEST['resume_coast_end'], true)."',
					resume_coast_currency = '". (int)$_REQUEST['resume_coast_currency']."',
					resume_coast_period = '".($_REQUEST['resume_coast_period'] == 1 ? 1 : 2)."',

					resume_ready_to_move = '". (int)$_REQUEST['resume_ready_to_move']."',
					resume_interview_method	 = '". addslashes(serialize($_REQUEST['resume_interview_method']))."',

					resume_terms_employment	 = '". addslashes(serialize($_REQUEST['resume_terms_employment']))."',
					resume_work_place	 = '". addslashes(serialize($_REQUEST['resume_work_place']))."',
					resume_employment_conditions	 = '". addslashes(serialize($_REQUEST['resume_employment_conditions']))."',
					resume_work_time	 = '". addslashes(serialize($_REQUEST['resume_work_time']))."',

					resume_dop_info_desc	 = '". clear_text($_REQUEST['resume_dop_info_desc'])."',
					resume_dop_info_passport	 = '". (int)($_REQUEST['resume_dop_info_passport'])."',

					resume_update = '".time()."',

					resume_owner = '". $_SESSION['user_id'] ."'
			");
		}

		$check_level_lang = 0;
		foreach ($_REQUEST['resume_skill_lang'] as $key => $value) {
			if($_REQUEST['resume_skill_lang'][$key] && !$_REQUEST['resume_skill_lang_level'][$key]) {
				$error[] = twig::$lang['jobs_skill_lang_error'];
			}
		}

		DB::query("DELETE FROM " . DB::$db_prefix . "_users_resume_lang WHERE resume_id = '". $_SESSION['user_id']."' ");

		foreach ($_REQUEST['resume_skill_lang'] as $key => $value) {
			if($_REQUEST['resume_skill_lang'][$key] && $_REQUEST['resume_skill_lang_level'][$key]) {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_resume_lang
					SET
						resume_id = '". $_SESSION['user_id']."',
						lang_id = '". (int)$_REQUEST['resume_skill_lang'][$key] ."',
						lang_level = '". (int)$_REQUEST['resume_skill_lang_level'][$key] ."'
				");
			}
		}

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_work
			WHERE
				work_owner = '". $_SESSION['user_id'] ."'
		");

		foreach ($_REQUEST['work_company'] as $key => $value) {

			$work_company = clear_text($_REQUEST['work_company'][$key]);
			$work_site = clear_text($_REQUEST['work_site'][$key]);
			$work_country = (int)($_REQUEST['work_country'][$key]);
			$work_city = (int)($_REQUEST['work_city'][$key]);
			$work_group = clear_text($_REQUEST['work_group'][$key]);
			$work_desc = clear_text($_REQUEST['work_desc'][$key]);
			$work_result = clear_text($_REQUEST['work_result'][$key]);
			$work_month_start = (int)($_REQUEST['work_month_start'][$key]);
			$work_month_end = (int)($_REQUEST['work_month_end'][$key]);
			$work_year_start = clear_text($_REQUEST['work_year_start'][$key]);
			$work_year_end = clear_text($_REQUEST['work_year_end'][$key]);
			$work_delete = (int)($_REQUEST['work_delete'][$key]);
			$work_now = (int)($_REQUEST['work_now'][$key]);
			$work_start_first = (int)($_REQUEST['work_start_first'][$key]);

			if((mb_strlen($work_company) < config::app('app_min_strlen') && mb_strlen($work_group) < config::app('app_min_strlen')) || ($_REQUEST['work_start_first'][0] == 1 && $key != 0)) {
				break;
			}

			if(mb_strlen($work_company) < config::app('app_min_strlen')) {
				$error[] = twig::$lang['work_company_error'];
			}
			if(mb_strlen($work_group) < config::app('app_min_strlen')) {
				$error[] = twig::$lang['work_group_error'];
			}

			try {
				v::url()->assert($work_site);
			} catch(ValidationException $exception) {
				$error[] = twig::$lang["work_site_error"];
			}

			if($work_now == 1) {
				if(!$work_year_start || mb_strlen((int)$work_year_start)<4) {
					$error[] = twig::$lang["work_year_error"];
				}

				try {
					v::key($work_month_start)->assert(twig::$lang["resume_work_month"]);
				} catch(ValidationException $exception) {
					$error[] = twig::$lang["work_month_error"];
				}
			} else {
				if($work_year_start>$work_year_end || !$work_year_start || !$work_year_end || mb_strlen((int)$work_year_start)<4 || mb_strlen((int)$work_year_end)<4) {
					$error[] = twig::$lang["work_year_error"];
				}

				try {
					v::key($work_month_start)->assert(twig::$lang["resume_work_month"]);
					v::key($work_month_end)->assert(twig::$lang["resume_work_month"]);
				} catch(ValidationException $exception) {
					$error[] = twig::$lang["work_month_error"];
				}
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

			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_work
				SET
					work_company = '".$work_company."',
					work_site = '".$work_site."',
					work_country = '".$work_country."',
					work_city = '".$work_city."',
					work_group = '".$work_group."',
					work_desc = '".$work_desc."',
					work_result = '".$work_result."',
					work_month_start = '".$work_month_start."',
					work_month_end = '".$work_month_end."',
					work_year_start = '".$work_year_start."',
					work_now = '".$work_now."',
					work_year_end = '".$work_year_end."',
					work_start_first = '".(($work_start_first == 1 && $key == 0) ? 1 : 0) ."',
					work_owner = '". $_SESSION['user_id'] ."'
			");
		}

		if($_REQUEST['resume_edu_title']) {
			DB::query("
				DELETE FROM
					" . DB::$db_prefix . "_users_edu
				WHERE
					resume_edu_owner = '". $_SESSION['user_id'] ."'
			");

			foreach ($_REQUEST['resume_edu_title'] as $key => $value) {
				if(
					(
						in_array($_REQUEST['resume_edu_title'][$key], [93,94,95,97]) && 
						!empty($_REQUEST['resume_edu_univer'][$key]) &&
						!empty($_REQUEST['resume_edu_univer_faq'][$key]) &&
						!empty($_REQUEST['resume_edu_univer_spec'][$key]) &&
						!empty($_REQUEST['resume_edu_country'][$key]) &&
						!empty($_REQUEST['resume_edu_city'][$key]) &&
						!empty($_REQUEST['resume_edu_year'][$key])
					) ||
					(
						in_array($_REQUEST['resume_edu_title'][$key], [98]) && 
						!empty($_REQUEST['resume_edu_univer'][$key]) &&
						!empty($_REQUEST['resume_edu_country'][$key]) &&
						!empty($_REQUEST['resume_edu_city'][$key]) &&
						!empty($_REQUEST['resume_edu_year'][$key])
					) ||
					(
						in_array($_REQUEST['resume_edu_title'][$key], [96,99]) && 
						!empty($_REQUEST['resume_edu_org'][$key]) &&
						!empty($_REQUEST['resume_edu_curs'][$key]) &&
						!empty($_REQUEST['resume_edu_univer_spec'][$key]) &&
						!empty($_REQUEST['resume_edu_country'][$key]) &&
						!empty($_REQUEST['resume_edu_city'][$key]) &&
						!empty($_REQUEST['resume_edu_year'][$key])
					) 
				) {
					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_edu
						SET
							resume_edu_title = '".(int)$_REQUEST['resume_edu_title'][$key]."',
							resume_edu_org = '".clear_text($_REQUEST['resume_edu_org'][$key])."',
							resume_edu_curs = '".clear_text($_REQUEST['resume_edu_curs'][$key])."',
							resume_edu_univer = '".clear_text($_REQUEST['resume_edu_univer'][$key])."',
							resume_edu_univer_spec = '".clear_text($_REQUEST['resume_edu_univer_spec'][$key])."',
							resume_edu_univer_faq = '".clear_text($_REQUEST['resume_edu_univer_faq'][$key])."',

							resume_edu_country = '".(int)$_REQUEST['resume_edu_country'][$key]."',
							resume_edu_city = '".(int)$_REQUEST['resume_edu_city'][$key]."',
							resume_edu_year = '".((int)$_REQUEST['resume_edu_year'][$key] ? (int)$_REQUEST['resume_edu_year'][$key] : "") ."',
							
							resume_edu_owner = '". $_SESSION['user_id'] ."'
					");
				}
			}
		}

		if(backend::isAjax()) {
			echo json_encode(array("upload"=>true, "status"=>"success"));
			exit;
		}

		$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/resume/".$_SESSION['user_id']."/"; // адрес директории с изображениями
		if($_REQUEST['resume_file_delete'] == 1) {
			if ($resume->resume_file) {
				@unlink($targetPath . $resume->resume_file);
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_resume
					SET
						resume_file = ''

					WHERE resume_owner = '". $_SESSION['user_id'] ."'
				");
			}
		}

		if($_FILES['resume_file'] && image_get_info($_FILES['resume_file']['tmp_name'])) {
			// сохранение фотографии

			if(!file_exists($targetPath)) {
				@mkdir($targetPath, 0777);
			}
			$file_tmp = $_FILES['resume_file']['tmp_name']; // изображения для данных
			$file_name = $_FILES['resume_file']['name']; // изображения для данных
			if(file_get_info($file_name)) {
				$file = rename_file($file_name, $targetPath); // переименовываем
			}

			if ($file != false) {
				if ($resume->resume_file) @unlink($targetPath . $resume->resume_file);
				$targetFile =  $targetPath . $file;
				move_uploaded_file($file_tmp, $targetFile);

				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_resume
					SET
						resume_file = '".$file."'

					WHERE resume_owner = '". $_SESSION['user_id'] ."'
				");
			}
		}

		header('Location:'.HOST_NAME.'/resume/');
		exit;
	}

	static function cropPhoto() {

		$resume_info = DB::single("SELECT resume_photo FROM " . DB::$db_prefix . "_users_resume WHERE resume_owner = '".$_SESSION['user_id']."' ");

		if($_REQUEST['x2']-$_REQUEST['x1']>=150 && $_REQUEST['y2']-$_REQUEST['y1']>=150 && $resume_info) {

			$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_resume_dir')."/"; // адрес директории с изображениями
			$targetPath2 = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_resume_orig_dir')."/"; // адрес директории с изображениями
			require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';

			$fileData = $targetPath.$resume_info;
			$fileData2 = $targetPath2.$resume_info;

			$thumb = new PHPThumb\GD($fileData2);
			$thumb->crop($_REQUEST['x1'], $_REQUEST['y1'], $_REQUEST['x2']-$_REQUEST['x1'], $_REQUEST['y2']-$_REQUEST['y1']);

			$thumb->save($fileData);

			$_REQUEST['thumb'] = $targetPath.$resume_info;

			thumb::bild_thumb(50, 50, 1);
			thumb::bild_thumb(100, 100, 1);
			thumb::bild_thumb(150, 150, 1);
			thumb::bild_thumb(150, 200, 1);
			thumb::bild_thumb(200, 200, 1);
			thumb::bild_thumb(250, 250, 1);
			thumb::bild_thumb(250, 300, 1);
		}

		header('Location:'.HOST_NAME.'/resume/');
		exit;
	}

	static function resume_open() {
		$resume_id = (int)$_REQUEST['resume_id'];

		$resume_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $resume_id ."'
		");
		$profile_info = get_user_info($resume_info->resume_owner);

		if(!$resume_info || !$profile_info) {
			site::error404();
		} else {

			$is_friend = siteFriends::is_friends($_SESSION['user_id'], $profile_info->id);
			twig::assign('is_friend', $is_friend);

			// title
			$page_info->page_title = twig::$lang['resume_page'];
			twig::assign('page_info', $page_info);
			// title

		
			$resume_info = self::resume_info($resume_info);

			$ready_to_move_list = get_book_for_essence(12);
			twig::assign('ready_to_move_list', $ready_to_move_list);

			$interview_method_list = get_book_for_essence(13);
			twig::assign('interview_method_list', $interview_method_list);

			$jobs_title = get_book_for_essence(21);
			twig::assign('jobs_title', $jobs_title);

			$jobs_edu = get_book_for_essence(22);
			twig::assign('jobs_edu', $jobs_edu);

			$terms_employment = get_book_for_essence(23);
			twig::assign('terms_employment', $terms_employment);

			$work_place = get_book_for_essence(24);
			twig::assign('work_place', $work_place);

			$employment_conditions = get_book_for_essence(10);
			twig::assign('employment_conditions', $employment_conditions);

			$work_time = get_book_for_essence(11);
			twig::assign('work_time', $work_time);

			$lang_list = get_book_for_essence(1);
			$lang_level_list = get_book_for_essence(14);
			twig::assign('lang_list', $lang_list);
			twig::assign('lang_level_list', $lang_level_list);

			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);

			$resume_skill_lang = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume_lang
				WHERE resume_id = '". $profile_info->id ."'
			");
			foreach ($resume_skill_lang as $key => $value) {
				$value->lang_id = $lang_list[$value->lang_id];
				$value->lang_level = $lang_level_list[$value->lang_level];
			}
			twig::assign('resume_skill_lang', $resume_skill_lang);

			// альбом
			$album_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume_album
				WHERE file_owner = '". $profile_info->id ."'
			");
			twig::assign('album_list', $album_list);
			// альбом

			
			// места работы
			$work_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_work
				WHERE work_owner = '". $profile_info->id ."'
			");
			foreach ($work_list as $key => $value) {
				if($value->work_year_start && $value->work_year_end) {
					$value->stage = $value->work_year_end - $value->work_year_start;
				} else {
					$value->stage = date("Y", time()) - $value->work_year_start;
				}

				$value->stage = declension($value->stage, twig::$lang['resume_stage']);

				$city_list = city_by_lang($value->work_country);
				$value->city_list = $city_list;
			}
			twig::assign('work_list', $work_list);
			// места работы

			$resume_edu = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_edu
				WHERE
					resume_edu_owner = '". $profile_info->id ."'
			");
			foreach ($resume_edu as $key => $value) {
				$value->city_list = city_by_lang($value->resume_edu_country);
			}
			twig::assign('resume_edu', $resume_edu);
			

			twig::assign('resume_info', $resume_info);
			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/perfomens_resume_open.tpl'));
		}
	}

	static function get_info_about_resume($resume_id) {
		$resume_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '".$resume_id."'
		");

		if($resume_info) {
			$resume_info = self::resume_info($resume_info);
		}

		return $resume_info;
	}

	static function resume_info($resume_info) {
		$currency_list = get_book_for_essence(5);

		$jobs_title = get_book_for_essence(21);
		$terms_employment = get_book_for_essence(23);
		$work_place = get_book_for_essence(24);
		$employment_conditions = get_book_for_essence(10);
		$work_time = get_book_for_essence(11);
		$interview_method_list = get_book_for_essence(13);

		$resume_info->resume_coast_currency = $currency_list[$resume_info->resume_coast_currency];
		$resume_info->resume_title = $jobs_title[$resume_info->resume_title];

		$resume_terms_employment = unserialize($resume_info->resume_terms_employment);
		$resume_info->resume_terms_employment = [];
		foreach ($resume_terms_employment as $key => $value) {
			$resume_info->resume_terms_employment[$value] = $terms_employment[$value];
		}

		$resume_work_place = unserialize($resume_info->resume_work_place);
		$resume_info->resume_work_place = [];
		foreach ($resume_work_place as $key => $value) {
			$resume_info->resume_work_place[$value] = $work_place[$value];
		}

		$resume_employment_conditions = unserialize($resume_info->resume_employment_conditions);
		$resume_info->resume_employment_conditions = [];
		foreach ($resume_employment_conditions as $key => $value) {
			$resume_info->resume_employment_conditions[$value] = $employment_conditions[$value];
		}

		$resume_work_time = unserialize($resume_info->resume_work_time);
		$resume_info->resume_work_time = [];
		foreach ($resume_work_time as $key => $value) {
			$resume_info->resume_work_time[$value] = $work_time[$value];
		}
		
		$resume_interview_method = unserialize($resume_info->resume_interview_method);
		$resume_info->resume_interview_method = [];
		foreach ($resume_interview_method as $key => $value) {
			$resume_info->resume_interview_method[$value] = $interview_method_list[$value];
		}

		$resume_info->resume_city = city_by_lang($resume_info->resume_country)[$resume_info->resume_city];
		$resume_info->resume_country = country_by_lang()[$resume_info->resume_country];
		$resume_info->resume_citizenship = country_by_lang()[$resume_info->resume_citizenship];


		$resume_info->resume_site = explode(",",$resume_info->resume_site);

		return $resume_info;
	}


	static function bild_resume_search_filter() {
		// поиск
		$page_link = "";
		$sql_join = "";
		$sql_link = "";

		if(!empty($_REQUEST['resume_owner'])) {
			$sql_link .= " AND resume_owner = '".(int)$_REQUEST['resume_owner']."' ";
			$page_link .= "&resume_owner=".(int)$_REQUEST['resume_owner'];
		}

		if($_REQUEST['resume_draft'] == 1) {
			$sql_link .= " AND resume_status != '1' AND resume_owner = '".(int)$_SESSION['user_id']."' ";
			$page_link .= "&resume_draft=1";
		} else {
			$sql_link .= " AND resume_status = '1' ";
		}

		if(!empty($_REQUEST['user_country'])) {
			$sql_link .= " AND resume_country = '".(int)$_REQUEST['user_country']."' ";
			$page_link .= "&user_country=".(int)$_REQUEST['user_country'];
		}

		if(!empty($_REQUEST['user_city'])) {
			$sql_link .= " AND resume_city = '".(int)$_REQUEST['user_city']."' ";
			$page_link .= "&user_city=".(int)$_REQUEST['user_city'];
		}

		if(!empty($_REQUEST['resume_title'])) {
			$sql_link .= " AND resume_title = '".(int)$_REQUEST['resume_title']."' ";
			$page_link .= "&resume_title=".(int)$_REQUEST['resume_title'];
		}

		if(!empty($_REQUEST['user_lang_default'])) {
			$sql_link .= " AND user_lang_default = '".(int)$_REQUEST['user_lang_default']."' ";
			$page_link .= "&user_lang_default=".(int)$_REQUEST['user_lang_default'];
		}

		if(!empty($_REQUEST['resume_skill_lang'])) {
			$sql_join .= " JOIN " . DB::$db_prefix . "_users_resume_lang as jjb on jjb.resume_id = resu.resume_owner ";
			$sql_link .= " AND jjb.lang_id = '".(int)$_REQUEST['resume_skill_lang']."' ";
			$page_link .= "&resume_skill_lang=".(int)$_REQUEST['resume_skill_lang'];
		}


		if(!empty($_REQUEST['resume_coast_start'])) {
			if($_REQUEST['resume_coast_start'] > 0) {

				$sql_link .= " AND resume_coast_start >= '".format_string_number($_REQUEST['resume_coast_start'], true)."'
				  ";

				$page_link .=
				 "&resume_coast_start=".format_string_number($_REQUEST['resume_coast_start']);
		 	}
		}

		if(!empty($_REQUEST['resume_coast_end'])) {
			if($_REQUEST['resume_coast_end'] > 0) {

				$sql_link .= " AND resume_coast_end <= '".format_string_number($_REQUEST['resume_coast_end'], true)."'
				  ";

				$page_link .=
				 "&resume_coast_end=".format_string_number($_REQUEST['resume_coast_end']);
		 	}
		}

		if(!empty($_REQUEST['resume_coast_currency'])) {

				$sql_link .= " AND resume_coast_currency = '".(int)$_REQUEST['resume_coast_currency']."'
				  ";

				$page_link .=
				 "&resume_coast_currency=".(int)$_REQUEST['resume_coast_currency'];

		}

		if(!empty($_REQUEST['resume_coast_period'])) {

				$sql_link .= " AND resume_coast_period = '".(int)$_REQUEST['resume_coast_period']."'
				  ";

				$page_link .=
				 "&resume_coast_period=".(int)$_REQUEST['resume_coast_period'];

		}

		return array($page_link, $sql_join, $sql_link);
	}

	static function resume_search() {
		$profile_id = (int)$_SESSION['user_id'];

		$profile_info = get_user_info($profile_id);

		if(!$profile_info) {
			site::error404();
		} else {
			// title
			$page_info->page_title = twig::$lang['resume_list'];
			twig::assign('page_info', $page_info);
			// title

			// сборка параметров для поиска
			$bild_filter = self::bild_resume_search_filter();
			$page_link = $bild_filter[0];
			$sql_join = $bild_filter[1];
			$sql_link = $bild_filter[2];
			// сборка параметров для поиска

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

			$limit = 10;
			// pager
			$start = get_current_page() * $limit - $limit;
			// pager

			$sql = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_resume as resu
				JOIN
					" . DB::$db_prefix . "_users as usr on resu.resume_owner = usr.id
				$sql_join
				WHERE user_status = '1' AND user_group = '3' $sql_link
				ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
				LIMIT " . $start . "," . $limit . "
			");

			$resume_list = [];
			foreach ($sql as $row) {
				$row->user_lang_default = $lang_list[$row->user_lang_default];
				$resume_list[$row->id] = self::resume_info($row);
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
			$num = DB::numrows("
				SELECT resume_owner FROM
					" . DB::$db_prefix . "_users_resume as resu
				JOIN
					" . DB::$db_prefix . "_users as usr on resu.resume_owner = usr.id
				$sql_join
				WHERE user_status = '1' AND user_group = '3' $sql_link
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/bank_resume/?filter=1". $page_link.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			twig::assign('resume_list', $resume_list);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/resume_list.tpl'));
		}
	}

	static function resume_access() {
		$profile_id = (int)$_SESSION['user_id'];
		$void_id = (int)$_REQUEST['void_id'];

		$resume_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $void_id ."' AND resume_status = '1'
		");
		$resume_info = self::resume_info($resume_info);

		if($_REQUEST['save']) {
			if($_SESSION['user_group'] != 4 || !$resume_info) {
				site::error404();
			} else {

				/*$check = DB::row("
					SELECT * FROM
						" . DB::$db_prefix . "_users_response
					WHERE response_owner = '".$profile_id."' AND response_resume = '".$resume_info->resume_id."' AND response_type = '1'
				");*/

				//if(!$check) {

					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_message
						SET
							message_from = '".$_SESSION['user_id']."',
							message_to = '".$void_id."',
							message_resume = '".$void_id."',
							message_desc = '".clear_text($_REQUEST['response_desc'])."',
							message_type = '12',
							message_date = '".time()."'
					");

					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_message
						SET
							message_from = '".$void_id."',
							message_to = '".$_SESSION['user_id']."',
							message_resume = '".$void_id."',
							message_desc = '".clear_text($_REQUEST['response_desc'])."',
							message_type = '12',
							message_date = '".time()."'
					");
					
					echo json_encode(array("respons"=>twig::$lang['resume_respons_success'], "status"=>"success"));

				/*} else {
					echo json_encode(array("title"=>twig::$lang['resume_owner_submit'], "html"=>twig::$lang['resume_respons_copy_error'], "status"=>"success"));
				}*/
			}

			exit;
		}
		
		twig::assign('resume_info', $resume_info);
		$html = twig::fetch('frontend/chank/modal_add_resume.tpl');
		echo json_encode(array("title"=>twig::$lang['resume_owner_submit'], "html"=>$html, "status"=>"success"));
		exit;
	}

	static function resume_accept() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 3) {
			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_to = '".$user_id."'
			");

			$resume_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume
				WHERE resume_id = '".$respons_info->response_resume."' AND resume_status = '1'
			");

			if($respons_info && $resume_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::resume_owner_perfomens_accept($respons_info->response_owner, $respons_id);
			}
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/briefcase/');
		}
		exit;
	}

	static function resume_deny() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 3) {
			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_to = '".$user_id."'
			");

			$resume_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_resume
				WHERE resume_id = '".$respons_info->response_resume."' AND resume_status = '1'
			");

			if($respons_info && $resume_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_not_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::resume_owner_perfomens_accept($respons_info->response_owner, $respons_id);
			}
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/briefcase/');
		}
		exit;
	}

	static function add_file_to_album() {

		if(!$_SESSION['user_id']) {
			access_404();
		}

		$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_album_resume_dir') . "/" . $_SESSION['user_id'] . "/";
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
							" . \DB::$db_prefix . "_users_resume_album
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
								"file" => ABS_PATH . "/".config::app('app_upload_dir')."/".config::app('app_album_resume_dir') . "/" . $_SESSION['user_id'] . "/" . $file_data,
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

	static function resume_update() {
		if($_SESSION['user_group'] == 3) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_resume
				SET resume_update = '".time()."'
				WHERE resume_owner = '".$_SESSION['user_id']."'
			");
		}

		header('Location:'.HOST_NAME.'/resume-'.$_SESSION['user_id'].'/');
		exit;
	}

	static function resume_status() {
		$resume_status = (int)$_REQUEST['resume_status'];
		$user_id = (int)$_SESSION['user_id'];

		DB::query("
			UPDATE
				" . DB::$db_prefix . "_users_resume
			SET
				resume_status = '".($resume_status == 1 ? 1 : 0)."',
				resume_update = '".time()."'
			WHERE resume_owner = '". $user_id ."'
		");

		header('Location:'.HOST_NAME.'/resume-'.$user_id.'/');
		exit;
	}

	static function resume_delete() {
		$user_id = (int)$_SESSION['user_id'];

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $user_id ."'
		");

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_resume_lang
			WHERE resume_id = '".$user_id."'
		");

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_work
			WHERE work_owner = '".$user_id."'
		");

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_edu
			WHERE resume_edu_owner = '".$user_id."'
		");

		$targetPath = BASE_DIR."/".config::app('app_upload_dir')."/".config::app('app_album_resume_dir') . "/" . $user_id . "/";
		rrmdir($targetPath);

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_resume_album
			WHERE file_owner = '".$user_id."'
		");

		header('Location:'.HOST_NAME.'/bank_resume/');
		exit;
	}

	static function resume_print() {
		$resume_id = (int)$_REQUEST['resume_id'];

		$resume_info = self::get_info_about_resume($resume_id);
		if($resume_info && $resume_info->resume_status == 1) {
			twig::assign('resume_info', $resume_info);

			$doc_text = twig::fetch('frontend/chank/resume_print.tpl');


			$doc_info->doc_text = $doc_text;

			$name_pdf = pdf::load($doc_info, $path);

			echo $doc_text;	
		}

		exit;
	}

}

?>
