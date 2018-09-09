<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteJobs {

	static function jobs() {
		// title
		$page_info->page_title = twig::$lang['jobs_add'];
		twig::assign('page_info', $page_info);
		// title

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);

		$experience_list = get_book_for_essence(3);
		twig::assign('experience_list', $experience_list);

		$jobs_title = get_book_for_essence(21);
		twig::assign('jobs_title', $jobs_title);

		$jobs_level_education = get_book_for_essence(22);
		twig::assign('jobs_level_education', $jobs_level_education);

		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);

		$lang_level_list = get_book_for_essence(14);
		twig::assign('lang_level_list', $lang_level_list);

		$terms_employment = get_book_for_essence(23);
		twig::assign('terms_employment', $terms_employment);

		$work_place = get_book_for_essence(24);
		twig::assign('work_place', $work_place);

		$employment_conditions = get_book_for_essence(10);
		twig::assign('employment_conditions', $employment_conditions);

		$work_time = get_book_for_essence(11);
		twig::assign('work_time', $work_time);

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 4) {
			site::error404();
		} else {

			$jobs_id = (int)$_REQUEST['jobs_id'];
			$jobs_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs
				WHERE jobs_owner = '". $_SESSION['user_id'] ."' AND jobs_id = '".$jobs_id."'
			");

			$city_list = city_by_lang($jobs_info->jobs_country);
			twig::assign('city_list', $city_list);
			$jobs_info = self::jobs_info($jobs_info);

			// country
			$country_list = country_by_lang();
			twig::assign('country_list', $country_list);
			// country

			twig::assign('jobs_info', $jobs_info);

			$jobs_info->jobs_skill_lang = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs_lang
				WHERE job_id = '". $jobs_id ."'
			");

			if($_REQUEST['save']) {
				self::saveJobs($jobs_info);
			}

			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/owner_jobs_edit.tpl'));
		}
	}

	static function saveJobs($jobs='') {
		$profile_id = (int)$_SESSION['user_id'];
		$jobs_id = (int)$_REQUEST['jobs_id'];
		$profile_info = get_user_info($profile_id);
		
		// the job is a new one ?
		if (!$jobs_id)
		    // check a user have appropriate acl
		    if (!(new CallBackHelper($profile_info))(ActionEnum::JobsCreate)) return;
		    

		if(!$profile_info || !$_SESSION['user_id'] || $profile_info->user_group != 4) {
			site::error404();
		}

		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);

		$experience_list = get_book_for_essence(3);
		twig::assign('experience_list', $experience_list);

		$jobs_title = get_book_for_essence(21);
		twig::assign('jobs_title', $jobs_title);

		$jobs_level_education = get_book_for_essence(22);
		twig::assign('jobs_level_education', $jobs_level_education);

		$lang_list = get_book_for_essence(1);
		twig::assign('lang_list', $lang_list);

		$lang_level_list = get_book_for_essence(14);
		twig::assign('lang_level_list', $lang_level_list);

		$terms_employment = get_book_for_essence(23);
		twig::assign('terms_employment', $terms_employment);

		$work_place = get_book_for_essence(24);
		twig::assign('work_place', $work_place);

		$employment_conditions = get_book_for_essence(10);
		twig::assign('employment_conditions', $employment_conditions);

		$work_time = get_book_for_essence(11);
		twig::assign('work_time', $work_time);

		$error = [];

		/*if($_REQUEST['jobs_coast_none'] == 1) {
			$_REQUEST['jobs_coast_start'] = "";
			$_REQUEST['jobs_coast_end'] = "";
			$_REQUEST['jobs_coast_currency'] = "";
		}*/

		try {
			v::key((int)$_REQUEST['jobs_title'])->assert($jobs_title);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["jobs_title_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}
		try {
			v::key((int)$_REQUEST['jobs_default_lang'])->assert($lang_list);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["jobs_default_lang_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(format_string_number($_REQUEST['jobs_coast_start'], true) < 0 && format_string_number($_REQUEST['jobs_coast_end'], true) < 0) {
			$error[] = twig::$lang["jobs_coast_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(mb_strlen($_REQUEST['jobs_company_title']) < config::app('app_min_strlen')) {
			$error[] = twig::$lang['jobs_company_title_error'];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(!$_REQUEST['jobs_country']) {
			$error[] = twig::$lang['jobs_country_error'];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}
		if(!$_REQUEST['jobs_city']) {
			$error[] = twig::$lang['jobs_city_error'];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		$check_level_lang = 0;
		foreach ($_REQUEST['jobs_skill_lang'] as $key => $value) {
			if($_REQUEST['jobs_skill_lang'][$key] && !$_REQUEST['jobs_skill_lang_level'][$key]) {
				$error[] = twig::$lang['jobs_skill_lang_error'];

				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}
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

		$jobs_level_education_other = end($jobs_level_education);
		if($jobs_level_education_other->id != $_REQUEST['jobs_level_education']) {
			$_REQUEST['jobs_level_education_ext'] = "";
		}

		if($jobs_id) {
			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_jobs
				SET
					jobs_title = '". (int)$_REQUEST['jobs_title']."',
					jobs_country = '". (int)$_REQUEST['jobs_country']."',
					jobs_city = '". (int)$_REQUEST['jobs_city']."',

					jobs_coast_start = '". format_string_number($_REQUEST['jobs_coast_start'], true)."',
					jobs_coast_end = '". format_string_number($_REQUEST['jobs_coast_end'], true)."',
					jobs_coast_currency = '". (int)$_REQUEST['jobs_coast_currency']."',
					jobs_coast_period = '".($_REQUEST['jobs_coast_period'] == 1 ? 1 : 2)."',

					jobs_company_title = '". clear_text($_REQUEST['jobs_company_title'])."',
					jobs_company_desc = '". clear_text($_REQUEST['jobs_company_desc'])."',

					jobs_stage = '". (int)$_REQUEST['jobs_stage']."',

					jobs_level_education = '". (int)$_REQUEST['jobs_level_education']."',
					jobs_level_education_ext	 = '". clear_text($_REQUEST['jobs_level_education_ext'])."',

					jobs_default_lang	 = '". (int)$_REQUEST['jobs_default_lang']."',

					jobs_terms_employment	 = '". addslashes(serialize($_REQUEST['jobs_terms_employment']))."',
					jobs_work_place	 = '". addslashes(serialize($_REQUEST['jobs_work_place']))."',
					jobs_employment_conditions	 = '". addslashes(serialize($_REQUEST['jobs_employment_conditions']))."',
					jobs_work_time	 = '". addslashes(serialize($_REQUEST['jobs_work_time']))."',

					jobs_desc	 = '". clear_text($_REQUEST['jobs_desc'])."',
					jobs_responsibilities	 = '". clear_text($_REQUEST['jobs_responsibilities'])."',
					jobs_requirements	 = '". clear_text($_REQUEST['jobs_requirements'])."',
					jobs_terms	 = '". clear_text($_REQUEST['jobs_terms'])."',

					jobs_edit = '".time()."'

				WHERE jobs_owner = '". $_SESSION['user_id'] ."' AND jobs_id = '".$jobs_id."'
			");
		} else {
			DB::query("
				INSERT INTO
					" . DB::$db_prefix . "_users_jobs
				SET
					jobs_title = '". (int)$_REQUEST['jobs_title']."',
					jobs_country = '". (int)$_REQUEST['jobs_country']."',
					jobs_city = '". (int)$_REQUEST['jobs_city']."',

					jobs_coast_start = '". format_string_number($_REQUEST['jobs_coast_start'], true)."',
					jobs_coast_end = '". format_string_number($_REQUEST['jobs_coast_end'], true)."',
					jobs_coast_currency = '". (int)$_REQUEST['jobs_coast_currency']."',
					jobs_coast_period = '".($_REQUEST['jobs_coast_period'] == 1 ? 1 : 2)."',

					jobs_company_title = '". clear_text($_REQUEST['jobs_company_title'])."',
					jobs_company_desc = '". clear_text($_REQUEST['jobs_company_desc'])."',

					jobs_stage = '". (int)$_REQUEST['jobs_stage']."',

					jobs_level_education = '". (int)$_REQUEST['jobs_level_education']."',
					jobs_level_education_ext	 = '". clear_text($_REQUEST['jobs_level_education_ext'])."',

					jobs_default_lang	 = '". (int)$_REQUEST['jobs_default_lang']."',

					jobs_terms_employment	 = '". addslashes(serialize($_REQUEST['jobs_terms_employment']))."',
					jobs_work_place	 = '". addslashes(serialize($_REQUEST['jobs_work_place']))."',
					jobs_employment_conditions	 = '". addslashes(serialize($_REQUEST['jobs_employment_conditions']))."',
					jobs_work_time	 = '". addslashes(serialize($_REQUEST['jobs_work_time']))."',

					jobs_desc	 = '". clear_text($_REQUEST['jobs_desc'])."',
					jobs_responsibilities	 = '". clear_text($_REQUEST['jobs_responsibilities'])."',
					jobs_requirements	 = '". clear_text($_REQUEST['jobs_requirements'])."',
					jobs_terms	 = '". clear_text($_REQUEST['jobs_terms'])."',

					jobs_add = '".time()."',

					jobs_owner = '". $_SESSION['user_id'] ."'
			");

			$jobs_id = DB::lastInsertId();			
		}

		DB::query("DELETE FROM " . DB::$db_prefix . "_users_jobs_lang WHERE job_id = '". $jobs_id."' ");

		foreach ($_REQUEST['jobs_skill_lang'] as $key => $value) {
			if($_REQUEST['jobs_skill_lang'][$key] && $_REQUEST['jobs_skill_lang_level'][$key]) {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_jobs_lang
					SET
						job_id = '". $jobs_id."',
						lang_id = '". (int)$_REQUEST['jobs_skill_lang'][$key] ."',
						lang_level = '". (int)$_REQUEST['jobs_skill_lang_level'][$key] ."'
				");
			}
		}

		header('Location:'.HOST_NAME.'/jobs-'.$jobs_id.'/');
		exit;
	}

	static function jobs_open() {
		$jobs_id = (int)$_REQUEST['jobs_id'];

		$jobs_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_jobs as jb
			JOIN " . DB::$db_prefix . "_users as usr on jb.jobs_owner = usr.id
			WHERE jobs_id = '". $jobs_id ."'
		");

		if(!$jobs_info || ($jobs_info->jobs_status == 0 && $jobs_info->jobs_owner != $_SESSION['user_id'])) {
			site::error404();
		} else {

			$jobs_info->jobs_skill_lang = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs_lang
				WHERE job_id = '". $jobs_id ."'
			");

			$lang_list = get_book_for_essence(1);
			$lang_level_list = get_book_for_essence(14);
			twig::assign('lang_list', $lang_list);
			twig::assign('lang_level_list', $lang_level_list);

			// title
			$page_info->page_title = twig::$lang['jobs_page'];
			twig::assign('page_info', $page_info);
			// title

			$jobs_info = self::jobs_info($jobs_info);
			$jobs_info = profile::profile_info($jobs_info);

			twig::assign('jobs_info', $jobs_info);

			// вакансии этого работодателя
			$jobs_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs as jb
				WHERE jobs_owner = '". $jobs_info->id ."' AND jobs_id != '".$jobs_id."'
				ORDER BY jobs_edit DESC, jobs_add DESC
				LIMIT 3
			");
			foreach ($jobs_list as $row) {
				$row = self::jobs_info($row);
			}
			twig::assign('jobs_list', $jobs_list);
			// вакансии этого работодателя


			// похожие
			$limit = $jobs_list ? 3 : 6;
			$jobs_more_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs as jb
				JOIN " . DB::$db_prefix . "_users as usr on jb.jobs_owner = usr.id
				WHERE jobs_id != '".$jobs_id."' AND jobs_title = '".$jobs_info->jobs_title->id."' AND jobs_country = '".$jobs_info->jobs_country['id']."' AND jobs_city = '".$jobs_info->jobs_city['id']."'
				ORDER BY jobs_edit DESC, jobs_add DESC
				LIMIT $limit
			");

			foreach ($jobs_more_list as $row) {
				$row = profile::profile_info($row);
				$row = self::jobs_info($row);
			}
			twig::assign('jobs_more_list', $jobs_more_list);
			// похожие

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/owner_jobs_open.tpl'));
		}
	}

	static function jobs_status() {
		$jobs_id = (int)$_REQUEST['jobs_id'];
		$jobs_status = (int)$_REQUEST['jobs_status'];
		$user_id = (int)$_SESSION['user_id'];

		DB::query("
			UPDATE
				" . DB::$db_prefix . "_users_jobs
			SET

				jobs_status = '".($jobs_status == 1 ? 1 : 0)."',

				jobs_edit = '".time()."'

			WHERE jobs_owner = '". $user_id ."' AND jobs_id = '".$jobs_id."'
		");

		header('Location:'.HOST_NAME.'/jobs-'.$jobs_id.'/');
		exit;
	}

	static function jobs_delete() {
		$jobs_id = (int)$_REQUEST['jobs_id'];
		$user_id = (int)$_SESSION['user_id'];

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_jobs
			WHERE jobs_owner = '". $user_id ."' AND jobs_id = '".$jobs_id."'
		");

		DB::query("
			DELETE FROM
				" . DB::$db_prefix . "_users_jobs_lang
			WHERE jobs_id = '".$jobs_id."'
		");

		header('Location:'.HOST_NAME.'/bank_vakansiy/');
		exit;
	}

	static function jobs_deny() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 4) {
			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_to = '".$user_id."'
			");
			$jobs_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs
				WHERE jobs_id = '".$respons_info->response_jobs."' AND jobs_status = '1'
			");

			if($respons_info && $jobs_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_not_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::jobs_owner_perfomens_accept($respons_info->response_perfomens, $respons_id);
			}

			/*$jobs_id = $respons_info->response_jobs;

			$message = twig::$lang['message_jobs_deny'] . "ID №".$jobs_id;
			siteMessage::message_add($_SESSION['user_id'], $respons_info->response_from, $message);*/
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/jobs-'.$jobs_id.'/');
		}
		exit;
	}

	static function jobs_accept() {
		$respons_id = (int)$_REQUEST['respons_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 4) {
			$respons_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_response
				WHERE response_id = '".$respons_id."' AND response_to = '".$user_id."'
			");
			$jobs_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_jobs
				WHERE jobs_id = '".$respons_info->response_jobs."' AND jobs_status = '1'
			");

			if($respons_info && $jobs_info) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users_response
					SET response_owner_accept = '1'
					WHERE response_id = '".$respons_id."'
				");

				siteBot::jobs_owner_perfomens_accept($respons_info->response_perfomens, $respons_id);
			}

			/*$jobs_id = $respons_info->response_jobs;

			$message = twig::$lang['message_jobs_deny'] . "ID №".$jobs_id;
			siteMessage::message_add($_SESSION['user_id'], $respons_info->response_from, $message);*/
		}

		if($_REQUEST['ref']) {
			header('Location:'.HOST_NAME.'/message/?message_to='.siteBot::$bot_id);
		} else {
			header('Location:'.HOST_NAME.'/jobs-'.$jobs_id.'/');
		}
		exit;
	}

	static function jobs_update() {
		$jobs_id = (int)$_REQUEST['jobs_id'];
		$user_id = (int)$_SESSION['user_id'];

		if($_SESSION['user_group'] == 4) {

			DB::query("
				UPDATE
					" . DB::$db_prefix . "_users_jobs
				SET jobs_edit = '".time()."'
				WHERE jobs_id = '".$jobs_id."' AND jobs_owner = '".$user_id."'
			");

		}

		header('Location:'.HOST_NAME.'/jobs-'.$jobs_id.'/');
		exit;
	}

	static function get_info_about_jobs($jobs_id) {
		$jobs_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_jobs
			WHERE jobs_id = '".$jobs_id."'
		");

		if($jobs_info) {
			$jobs_info = self::jobs_info($jobs_info);
		}

		return $jobs_info;
	}

	static function jobs_info($jobs_info) {
		$currency_list = get_book_for_essence(5);
		$experience_list = get_book_for_essence(3);
		$jobs_title = get_book_for_essence(21);
		$jobs_level_education = get_book_for_essence(22);
		$lang_list = get_book_for_essence(1);
		$lang_level_list = get_book_for_essence(14);
		$terms_employment = get_book_for_essence(23);
		$work_place = get_book_for_essence(24);
		$employment_conditions = get_book_for_essence(10);
		$work_time = get_book_for_essence(11);


		$jobs_info->jobs_coast_currency = $currency_list[$jobs_info->jobs_coast_currency];
		$jobs_info->jobs_title = $jobs_title[$jobs_info->jobs_title];
		$jobs_info->jobs_default_lang = $lang_list[$jobs_info->jobs_default_lang];
		$jobs_info->jobs_level_education = $jobs_level_education[$jobs_info->jobs_level_education];

		$jobs_terms_employment = unserialize($jobs_info->jobs_terms_employment);
		$jobs_info->jobs_terms_employment = [];
		foreach ($jobs_terms_employment as $key => $value) {
			$jobs_info->jobs_terms_employment[$value] = $terms_employment[$value];
		}

		$jobs_work_place = unserialize($jobs_info->jobs_work_place);
		$jobs_info->jobs_work_place = [];
		foreach ($jobs_work_place as $key => $value) {
			$jobs_info->jobs_work_place[$value] = $work_place[$value];
		}

		$jobs_employment_conditions = unserialize($jobs_info->jobs_employment_conditions);
		$jobs_info->jobs_employment_conditions = [];
		foreach ($jobs_employment_conditions as $key => $value) {
			$jobs_info->jobs_employment_conditions[$value] = $employment_conditions[$value];
		}

		$jobs_work_time = unserialize($jobs_info->jobs_work_time);
		$jobs_info->jobs_work_time = [];
		foreach ($jobs_work_time as $key => $value) {
			$jobs_info->jobs_work_time[$value] = $work_time[$value];
		}
		
		$country_list = country_by_lang();
		$jobs_city = city_by_lang($jobs_info->jobs_country);
		$jobs_info->jobs_country = $country_list[$jobs_info->jobs_country];
		$jobs_info->jobs_city = $jobs_city[$jobs_info->jobs_city];

		$jobs_info->jobs_add = unix_to_date($jobs_info->jobs_add);
		$jobs_info->jobs_edit = unix_to_date($jobs_info->jobs_edit);

		return $jobs_info;
	}

	static function bild_jobs_search_filter() {
		// поиск
		$page_link = "";
		$sql_join = "";
		$sql_link = "";

		if(!empty($_REQUEST['jobs_owner'])) {
			$sql_link .= " AND jobs_owner = '".(int)$_REQUEST['jobs_owner']."' ";
			$page_link .= "&jobs_owner=".(int)$_REQUEST['jobs_owner'];
		}

		if($_REQUEST['jobs_draft'] == 1) {
			$sql_link .= " AND jobs_status != '1' AND jobs_owner = '".(int)$_SESSION['user_id']."' ";
			$page_link .= "&jobs_draft=1";
		} else {
			$sql_link .= " AND jobs_status = '1' ";
		}

		if(!empty($_REQUEST['user_country'])) {
			$sql_link .= " AND jobs_country = '".(int)$_REQUEST['user_country']."' ";
			$page_link .= "&user_country=".(int)$_REQUEST['user_country'];
		}

		if(!empty($_REQUEST['user_city'])) {
			$sql_link .= " AND jobs_city = '".(int)$_REQUEST['user_city']."' ";
			$page_link .= "&user_city=".(int)$_REQUEST['user_city'];
		}

		if(!empty($_REQUEST['jobs_title'])) {
			$sql_link .= " AND jobs_title = '".(int)$_REQUEST['jobs_title']."' ";
			$page_link .= "&jobs_title=".(int)$_REQUEST['jobs_title'];
		}

		if(!empty($_REQUEST['jobs_default_lang'])) {
			$sql_link .= " AND jobs_default_lang = '".(int)$_REQUEST['jobs_default_lang']."' ";
			$page_link .= "&jobs_default_lang=".(int)$_REQUEST['jobs_default_lang'];
		}

		if(!empty($_REQUEST['jobs_skill_lang'])) {
			$sql_join .= " JOIN " . DB::$db_prefix . "_users_jobs_lang as jjb on jjb.job_id = jbs.jobs_id ";
			$sql_link .= " AND jjb.lang_id = '".(int)$_REQUEST['jobs_skill_lang']."' ";
			$page_link .= "&jobs_skill_lang=".(int)$_REQUEST['jobs_skill_lang'];
		}


		if(!empty($_REQUEST['jobs_coast_start'])) {
			if($_REQUEST['jobs_coast_start'] > 0) {

				$sql_link .= " AND jobs_coast_start >= '".format_string_number($_REQUEST['jobs_coast_start'], true)."'
				  ";

				$page_link .=
				 "&jobs_coast_start=".format_string_number($_REQUEST['jobs_coast_start']);
		 	}
		}

		if(!empty($_REQUEST['jobs_coast_end'])) {
			if($_REQUEST['jobs_coast_end'] > 0) {

				$sql_link .= " AND jobs_coast_end <= '".format_string_number($_REQUEST['jobs_coast_end'], true)."'
				  ";

				$page_link .=
				 "&jobs_coast_end=".format_string_number($_REQUEST['jobs_coast_end']);
		 	}
		}

		if(!empty($_REQUEST['jobs_coast_currency'])) {

				$sql_link .= " AND jobs_coast_currency = '".(int)$_REQUEST['jobs_coast_currency']."'
				  ";

				$page_link .=
				 "&jobs_coast_currency=".(int)$_REQUEST['jobs_coast_currency'];

		}

		if(!empty($_REQUEST['jobs_coast_period'])) {

				$sql_link .= " AND jobs_coast_period = '".(int)$_REQUEST['jobs_coast_period']."'
				  ";

				$page_link .=
				 "&jobs_coast_period=".(int)$_REQUEST['jobs_coast_period'];

		}

		return array($page_link, $sql_join, $sql_link);
	}

	static function jobs_search() {
		$profile_id = (int)$_SESSION['user_id'];

		// check a user have an appropiate acl
		if (!(new CallBackHelper(get_user_info($profile_id)))(ActionEnum::JobsList)) return;
		
		/*$profile_info = get_user_info($profile_id);

		if(!$profile_info) {
			site::error404();
		} else {*/

			// title
			$page_info->page_title = twig::$lang['jobs_list'];
			twig::assign('page_info', $page_info);
			// title

			// сборка параметров для поиска
			$bild_filter = self::bild_jobs_search_filter();
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
					" . DB::$db_prefix . "_users_jobs as jbs
				JOIN
					" . DB::$db_prefix . "_users as usr on jbs.jobs_owner = usr.id
				$sql_join
				WHERE user_status = '1' AND user_group = '4' $sql_link
				ORDER BY jbs.jobs_id DESC
				LIMIT " . $start . "," . $limit . "
			");


			$jobs_list = [];
			foreach ($sql as $row) {
				$row = self::jobs_info($row);

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

			// языковые пары
			/*$lang_var = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_langvar
				WHERE var_owner IN (
						SELECT id FROM
							" . DB::$db_prefix . "_users_jobs as jbs
						JOIN
							" . DB::$db_prefix . "_users as usr on jbs.jobs_owner = usr.id
						WHERE user_status = '1' AND user_group = '4' AND jobs_status = '1' $sql_link
					)
			");
			$jobs_owner = [];
			foreach ($lang_var as $row) {
				$row->var_lang_from = $lang_list[$row->var_lang_from];
				$row->var_lang_to = $lang_list[$row->var_lang_to];

				$jobs_owner[$row->var_owner][] = $row;
			}

			foreach ($jobs_list as $key => $value) {
				# code...
			}
			dd($lang_var);*/
			// языковые пары

			// pager
			$num = DB::numrows("
				SELECT jbs.jobs_id FROM
					" . DB::$db_prefix . "_users_jobs as jbs
				JOIN
					" . DB::$db_prefix . "_users as usr on jbs.jobs_owner = usr.id
				$sql_join
				WHERE user_status = '1' AND user_group = '4' $sql_link
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' .HOST_NAME."/bank_vakansiy/?filter=1". $page_link.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			twig::assign('jobs_list', $jobs_list);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/jobs_list.tpl'));
		/*}*/
	}

	static function jobs_access() {
		$profile_id = (int)$_SESSION['user_id'];
		$void_id = (int)$_REQUEST['void_id'];
		$profile_info = get_user_info($profile_id);

		$resume_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_resume
			WHERE resume_owner = '". $profile_id ."' AND resume_status = '1'
		");
		$jobs_info = DB::row("
			SELECT * FROM
				" . DB::$db_prefix . "_users_jobs
			WHERE jobs_id = '". $void_id ."' AND jobs_status = '1'
		");
		$jobs_info = self::jobs_info($jobs_info);

		if($_REQUEST['save']) {
			if(!$profile_info || $_SESSION['user_group'] != 3 || !$jobs_info) {
				site::error404();
			} else {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_message
					SET
						message_from = '".$_SESSION['user_id']."',
						message_to = '".$jobs_info->jobs_owner."',
						message_jobs = '".$void_id."',
						message_desc = '".clear_text($_REQUEST['response_desc'])."',
						message_type = '13',
						message_date = '".time()."'
				");

				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_message
					SET
						message_from = '".$jobs_info->jobs_owner."',
						message_to = '".$_SESSION['user_id']."',
						message_jobs = '".$void_id."',
						message_desc = '".clear_text($_REQUEST['response_desc'])."',
						message_type = '13',
						message_date = '".time()."'
				");
				
				echo json_encode(array("respons"=>twig::$lang['jobs_respons_success'], "status"=>"success"));
			}
			exit;
		}

		twig::assign('jobs_info', $jobs_info);
		$html = twig::fetch('frontend/chank/modal_add_jobs.tpl');
		echo json_encode(array("title"=>twig::$lang['jobs_respons_title'], "html"=>$html, "status"=>"success"));
		exit;
	}


	static function jobs_print() {
		$jobs_id = (int)$_REQUEST['jobs_id'];

		$jobs_info = self::get_info_about_jobs($jobs_id);
		if($jobs_info && $jobs_info->jobs_status == 1) {
			twig::assign('jobs_info', $jobs_info);

			$doc_text = twig::fetch('frontend/chank/jobs_print.tpl');


			$doc_info->doc_text = $doc_text;

			$name_pdf = pdf::load($doc_info, $path);



			echo $doc_text;	
		}

		exit;
	}


}

?>
