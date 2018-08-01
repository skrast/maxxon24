<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteTranslations {

	static function translations_list() {

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);
		if(!$profile_info) {
			site::error404();
		} else {

			// title
			$page_info->page_title = twig::$lang['service_translations'];
			twig::assign('page_info', $page_info);
			// title

			$group = (int)$_SESSION['user_group'];
			$status = $_REQUEST['status'] ? (int)$_REQUEST['status'] : 0;

			$sql = "1=1";
			$join = "";
			switch ($group) {
				case '2': // Редакторы
					$sql .= "";
				break;
				case '3': // Исполнители
					$join .= " JOIN " . DB::$db_prefix . "_users_writer_invite as inv on wr.document_id = inv.document_id";
					$sql .= " AND inv.perfomens_id = '".$profile_id."' ";
				break;
				case '4': // Заказчики
					$sql .= " AND document_owner = '".$profile_id."' ";
				break;
			}

			switch ($status) {
				case '0': // Редакторы
					$sql .= "";
				break;
				case '1': // Исполнители
					$sql .= " AND document_status = '1' ";
				break;
				case '2': // Заказчики
					$sql .= " AND document_status = '0' ";
				break;
			}

			$limit = 10;
			$start = get_current_page() * $limit - $limit;
			
			$document_list = DB::fetchrow("
				SELECT * FROM
					" . DB::$db_prefix . "_users_writer as wr
				$join
				WHERE $sql
				ORDER BY wr.document_id DESC
				LIMIT " . $start . "," . $limit . "
			");

			foreach($document_list as $row) {
				$row = siteSearch::document_info($row);
			}

			//dd($document_list);

			twig::assign('document_list', $document_list);

			// pager
			$num = DB::numrows("
				SELECT wr.document_id FROM
					" . DB::$db_prefix . "_users_writer as wr
				$join
				WHERE $sql
			");
			twig::assign('num', $num);
			if ($num > $limit)
			{
				$page_nav = get_pagination(ceil($num / $limit), get_current_page(), '<a href="' . HOST_NAME . site::$link_lang_pref . "/translations/?status=". $status.'&page={s}">{t}</a>');
				\twig::assign('page_nav', $page_nav);
			}
			// pager

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/translations_list.tpl'));

		}
	}


	static function translations_open() {

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);
		if(!$profile_info) {
			site::error404();
		} else {

			// title
			$page_info->page_title = twig::$lang['service_translations'];
			twig::assign('page_info', $page_info);
			// title

			$group = (int)$_SESSION['user_group'];
			$document_id = (int)$_REQUEST['document_id'];

			$sql = "";
			$join = "";
			switch ($group) {
				case '2': // Редакторы
					$sql .= "";
				break;
				case '3': // Исполнители
					$join .= " JOIN " . DB::$db_prefix . "_users_writer_invite as inv on wr.document_id = inv.document_id";
					$sql .= " AND inv.perfomens_id = '".$profile_id."' ";
				break;
				case '4': // Заказчики
					$sql .= " AND document_owner = '".$profile_id."' ";
				break;
			}

			$document_info = DB::row("
				SELECT wr.* FROM
					" . DB::$db_prefix . "_users_writer as wr
				$join
				WHERE wr.document_id = '".$document_id."' $sql
			");
			$document_info = siteSearch::document_info($document_info);
			twig::assign('document_info', $document_info);

			if($document_info->document_status != 1 && $_SESSION['user_id'] != $document_info->document_owner->id && !in_array($_SESSION['user_group'], [1,2])) {
				site::error404();
			} else {

				$sql = DB::fetchrow("
					SELECT * FROM
						" . DB::$db_prefix . "_users_writer_invite as inv
					JOIN " . DB::$db_prefix . "_users as usr on usr.id = inv.perfomens_id
					WHERE user_status = '1' AND user_group = '3' AND inv.document_id = '".$document_info->document_id."'
				");
				$p_check = [];
				foreach ($sql as $row) {
					$p_check[] = $row->id;
				}

				// исполнители
				if($_SESSION['user_group'] == 3 && $document_info->document_status == 1 && in_array($_SESSION['user_id'], $p_check)) { 
					if($_REQUEST['perfomens_desc']) {
						DB::query("
							UPDATE
								" . DB::$db_prefix . "_users_writer_invite
							SET 
								perfomens_desc = '".clear_text($_REQUEST['perfomens_desc'])."',
								perfomens_desc_date = '".time()."'
							WHERE document_id = '".$document_info->document_id."' AND perfomens_id = '".$profile_id."'
						");

						echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
						exit;
					}

					$perfomens_respons = DB::row("
						SELECT * FROM
							" . DB::$db_prefix . "_users_writer_invite
						WHERE document_id = '".$document_id."' AND perfomens_id = '".$profile_id."'
					");
					$perfomens_respons->perfomens_desc_date = unix_to_date($perfomens_respons->perfomens_desc_date);
					twig::assign('perfomens_respons', $perfomens_respons);
				}
				// исполнители


				// список откликов
				if($_SESSION['user_id'] == $document_info->document_owner->id || in_array($_SESSION['user_group'], [1,2])) { 
					$perfomer_list = [];
					$perfomer_list_var = [];

					// country
					$country_list = country_by_lang();
					twig::assign('country_list', $country_list);
					// country

					// справочник по языкам
					$lang_list = get_book_for_essence(1);
					twig::assign('lang_list', $lang_list);
					// справочник по языкам

					$sql = DB::fetchrow("
						SELECT * FROM
							" . DB::$db_prefix . "_users_writer_invite as inv
						JOIN " . DB::$db_prefix . "_users as usr on usr.id = inv.perfomens_id
						WHERE user_status = '1' AND user_group = '3' AND inv.document_id = '".$document_info->document_id."'
						ORDER BY perfomens_desc DESC, user_rang DESC, user_visittime DESC, user_in_work ASC
					");
					foreach ($sql as $row) {
						$row->perfomens_desc_date = unix_to_date($row->perfomens_desc_date);
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
				}
				// список откликов


				// рассылка приглашений
				if($_REQUEST['document_perfomens'] && in_array($_SESSION['user_group'], [1,2])) { 
					self::translations_invite($document_info);
				}
				// рассылка приглашений

				
				// смена статуса
				if($_REQUEST['document_status'] &&
					(
						$_SESSION['user_id'] == $document_info->document_owner->id || in_array($_SESSION['user_group'], [1,2])
					)
				) { 
					DB::query("
						UPDATE
							" . DB::$db_prefix . "_users_writer
						SET 
							document_status = '".($_REQUEST['document_status'] == 1 ? 1 : 0)."'
						WHERE document_id = '".$document_info->document_id."'
					");

					echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
					exit;
				}
				// смена статуса


				$perfomens_list = DB::fetchrow("
					SELECT * FROM
						" . DB::$db_prefix . "_users
					WHERE user_status = '1' AND user_group = '3'
					ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
					LIMIT 20
				");
				foreach($perfomens_list as $row) {
					$row = profile::profile_info($row);
				}
				twig::assign('perfomens_list', $perfomens_list);


				$html = twig::fetch('frontend/chank/perfomens_col.tpl');
				twig::assign('perfomens_col', $html);

				// шаблон страницы
				twig::assign('content', twig::fetch('frontend/translations_open.tpl'));
			}

		}
	}

	static function translations_attach() {
		$document_id = (int)$_REQUEST['document_id'];
		$attach_name = $_REQUEST['file'];

		$sql = "";
		$join = "";
		switch ($group) {
			case '2': // Редакторы
				$sql .= "";
			break;
			case '3': // Исполнители
				$join .= " JOIN " . DB::$db_prefix . "_users_writer_invite as inv on wr.document_id = inv.document_id";
				$sql .= " AND inv.perfomens_id = '".$profile_id."' ";
			break;
			case '4': // Заказчики
				$sql .= " AND document_owner = '".$profile_id."' ";
			break;
		}
		$document_info = DB::row("
			SELECT wr.* FROM
				" . DB::$db_prefix . "_users_writer as wr
			$join
			WHERE wr.document_id = '".$document_id."' $sql
		");
		$document_info = siteSearch::document_info($document_info);


		$sql = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users_writer_invite as inv
			JOIN " . DB::$db_prefix . "_users as usr on usr.id = inv.perfomens_id
			WHERE user_status = '1' AND user_group = '3' AND inv.document_id = '".$document_info->document_id."'
		");
		$p_check = [];
		foreach ($sql as $row) {
			$p_check[] = $row->id;
		}
		
		
		// исполнители
		if($document_info->document_status == 1 && 
			(
				in_array($_SESSION['user_id'], $p_check) ||
				in_array($_SESSION['user_group'], [1,2]) || 
				$_SESSION['user_id'] == $document_info->document_owner->id
			) && 
			in_array($attach_name, $document_info->document_file)
		) { 

			$targetPath = BASE_DIR . "/".config::app('app_upload_dir')."/writer/".$document_id."/";
			$get_file = $targetPath . $attach_name;

			$mime = mime_content_type($get_file);
			header('content-type: '.$mime);
			if(isset($_REQUEST['download'])) {
				header('Content-Disposition: attachment; filename='.$original_name);
			}
			echo file_get_contents($get_file);
		} else {
			header('Location:' . ABS_PATH);
		}
		exit;
	}


	static function translations_invite($document_info) {

		if(!in_array($_SESSION['user_group'], [1,2])) {
			exit;
		}

		$document_perfomens = $_REQUEST['document_perfomens'];

		$perfomens_list = DB::fetchrow("
			SELECT * FROM
				" . DB::$db_prefix . "_users
			WHERE user_status = '1' AND user_group = '3' AND id IN (".implode(",", $document_perfomens).")
			ORDER BY user_rang DESC, user_visittime DESC, user_in_work ASC
			LIMIT 20
		");
		foreach($perfomens_list as $row) {
			$row = profile::profile_info($row);
		}

		$sql = DB::fetchrow("
			SELECT perfomens_id FROM
				" . DB::$db_prefix . "_users_writer_invite
			WHERE
				document_id = '".$document_info->document_id."'
		");
		$perfomens_id_list = [];
		foreach ($sql as $key => $value) {
			$perfomens_id_list[] = $value->perfomens_id;
		}

		foreach($perfomens_list as $row) {
			if(!in_array($row->id, $perfomens_id_list)) {
				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_writer_invite
					SET 
						document_id = '".$document_info->document_id."',
						perfomens_id = '".$row->id."',
						date = '".time()."'
				");


				siteBot::document_invite($row->id, $document_info->document_id);
			}
		}

		echo json_encode(array("respons"=>twig::$lang['service_translations_search_perfomens_invite'], "status"=>"success"));
		exit;
	}
	

}

?>
