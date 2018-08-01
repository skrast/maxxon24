<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class book {
	static $log_setting = 1;
	static $main_title =  array();
	static $book_type_array = "";
	static $field_custom_types = "";


	function __construct() {
		self::$main_title[] =  twig::$lang["book_name"];

		self::$book_type_array = book::get_type_book();
		twig::assign('book_type_array', self::$book_type_array);
	}

	static function work() {

	}

	static function validation_permission() {
		try {
			v::key('alles')->assert($_SESSION);
		} catch(ValidationException $exception) {
			return false;
		}
		return true;
	}

	static function check_permission() {
		if(self::validation_permission() === false) {
			access_404();
		}
	}

	// получение справочников по типам 0-системные
	static function get_type_book($type=0) {
		$clear_cache = ($type==0) ? config::app('CACHE_LIFETIME_LONG') : "";

		$sql = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_book_custom
			WHERE type = '".($type ? 1 : 0)."'
			ORDER BY id ASC
		", $clear_cache);
        $type_list = [];
		foreach ($sql as $row) {
			$type_list[$row->id] = $row;
		}

		return $type_list;
	}
	// получение справочников по типам 0-системные

	static function bild_page() {
		self::check_permission();

		if(isset($_REQUEST['book_id'])) {
			$book_id = (int)$_REQUEST['book_id'];
			if(array_key_exists($book_id, self::$book_type_array)) {
				$book = get_book_for_essence($book_id);
				twig::assign('book', $book);
			}
			twig::assign('book_id', $book_id);
		}
		twig::assign('content', twig::fetch('book/book.tpl'));
	}

	// редактирвоание справочника
	static function book_nest() {
		self::check_permission();

		$book_id = (int)$_REQUEST['book_id'];
		try {
			v::key($book_id)->assert(self::$book_type_array);
		} catch(ValidationException $exception) {
			access_404();
		}

		// сохранение позиций
		if(isset($_REQUEST['nest'])) {
			$nest = json_decode(stripslashes($_REQUEST['nest']));
			foreach ($nest as $key => $value) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_book
					SET
						sort = '".((int)$key)."'
					WHERE id = '".(int)$value->id."'
				");
			}
		}
		// сохранение позиций

		get_book_for_essence($book_id, "-1");
		exit;
	}
	// редактирвоание справочника

	static function book_work() {
		self::check_permission();

		$type = (int)$_REQUEST['essense_id'];
		$id = (int)$_REQUEST['void_id'];

		if(isset($_REQUEST['save'])) {
			$title = clear_text($_REQUEST['title']);
			$color = clear_text($_REQUEST['color']);

			$error = [];

			try {
				v::key($type)->assert(self::$book_type_array);
			} catch(ValidationException $exception) {
				access_404();
			}

			try {
			    v::length(config::app('app_min_strlen'), null)->assert($title);
			} catch(ValidationException $exception) {
			    $error[] = twig::$lang["book_error_name"];
			}

			$title_lang_temp = $_REQUEST['title_lang'];
			foreach ($title_lang_temp as $key => $value) {
				$lang = clear_text($value);
				$title_lang[$key] = $lang;

				/*try {
					v::length(config::app('app_min_strlen'), null)->assert($lang);


				} catch(ValidationException $exception) {
					$error[] = twig::$lang["book_error_name"];
				}*/
			}

            $check_id = 0;
			if(!empty($id)) {
				$check_id = DB::single("SELECT id FROM " . DB::$db_prefix . "_book WHERE id = '".$id."'");

				try {
				    v::numeric()->identical($check_id)->assert($id);
				} catch(ValidationException $exception) {
				    $error[] = twig::$lang["book_error_no_item"];
				}
			}

			if($error) {
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if($check_id && $id) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_book
					SET
						title = '".$title . "',
						title_lang = '".serialize($title_lang) . "',
						color = '".$color . "',
						type = '".$type . "'
					WHERE
						id = '".$id."'
				");

				logs::add(twig::$lang['book_edit_log'] .' ('.$type.')', self::$log_setting);

			} else {
				$sort = DB::numrows("
					SELECT id
					FROM " . DB::$db_prefix . "_book
					WHERE type = '".$type . "'
				");

				DB::query("
					INSERT INTO " . DB::$db_prefix . "_book
					SET
						title = '".$title . "',
						title_lang = '".serialize($title_lang) . "',
						color = '".$color . "',
						sort = '".($sort+1) . "',
						type = '".$type . "'
				");
				logs::add(twig::$lang['book_add_log'] .' ('.$type.')', self::$log_setting);

			}
			get_book_for_essence($type, "-1");
			echo json_encode(array("ref"=>ABS_PATH_ADMIN_LINK.'?do=book&book_id='.$type, "status"=>"success"));
			exit;
		}

		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);

		if(!empty($id)) {
			$book = DB::row("
				SELECT * FROM " . DB::$db_prefix . "_book WHERE id = '".$id."'
			");
			if($book) {
				$book = self::book_info($book);
			}
			twig::assign('book', $book);
		}

		twig::assign('void_id', $id);
		twig::assign('essense_id', $type);

		$html = twig::fetch('chank/book_work.tpl');
		$title = (!empty($id)) ? twig::$lang["book_edit"] : twig::$lang["book_add"];
		echo json_encode(array("title"=>$title,"html"=>$html, "status"=>"success"));
		exit;
	}

	static function book_info($book_info) {
		$book_info->title_lang = unserialize($book_info->title_lang);
		$book_info->title_default = $book_info->title;
		$book_info->title = ($book_info->title_lang[$_SESSION['user_lang']]) ? $book_info->title_lang[$_SESSION['user_lang']] : $book_info->title;

		return $book_info;
	}

	static function book_delete() {
		self::check_permission();

		$book_item_id = (int)$_REQUEST['book_item_id'];
		$book_id = (int)$_REQUEST['book_id'];

		$check_book_id = DB::row("
			SELECT * FROM " . DB::$db_prefix . "_book WHERE id = '".$book_item_id."' AND type = '".$book_id."'
		");

		if($check_book_id) {
			$new_book_id = DB::single("
				SELECT id FROM " . DB::$db_prefix . "_book WHERE id != '".$book_item_id."' AND type = '".$check_book_id->type."' ORDER BY sort ASC LIMIT 1
			");

			if($new_book_id) {
				switch ($check_book_id->type) {
					case '1':
						DB::query("UPDATE " . DB::$db_prefix . "_company set company_status = '".$new_book_id."' WHERE company_status = '".$book_item_id."' ");
					break;

					case '2':
						DB::query("UPDATE " . DB::$db_prefix . "_task set task_status = '".$new_book_id."' WHERE task_status = '".$book_item_id."' ");
					break;

					case '3':
						DB::query("UPDATE " . DB::$db_prefix . "_deal set deal_status = '".$new_book_id."' WHERE deal_status = '".$book_item_id."' ");

						$deal = DB::fetchrow("SELECT * FROM " . DB::$db_prefix . "_deal_status WHERE status_id = '".$book_item_id."' GROUP BY deal_id ");
						foreach ($deal as $key => $value) {
							DB::query("
								INSERT INTO " . DB::$db_prefix . "_deal_status
								SET
									deal_id = '".$value->deal_id."',
									status_id = '".$new_book_id."',
									status_start_date = '".time()."',
									status_user_id = '".$_SESSION['user_id']."'
							");
						}

						DB::query("DELETE FROM " . DB::$db_prefix . "_deal_status WHERE status_id = '".$book_item_id."' ");
					break;

					case '4':
						DB::query("UPDATE " . DB::$db_prefix . "_company set company_status = '".$new_book_id."' WHERE company_status = '".$book_item_id."' ");
					break;

					case '5':
						DB::query("UPDATE " . DB::$db_prefix . "_task set task_type = '".$new_book_id."' WHERE task_type = '".$book_item_id."' ");
					break;

					case '6':
						DB::query("UPDATE " . DB::$db_prefix . "_deal set deal_invite = '' WHERE deal_invite = '".$book_item_id."' ");
					break;

					case '7':
						DB::query("UPDATE " . DB::$db_prefix . "_deal set deal_deny = '' WHERE deal_deny = '".$book_item_id."' ");
					break;
				}

				DB::query("DELETE FROM " . DB::$db_prefix . "_book WHERE id = '".$book_item_id."' ");

				get_book_for_essence($book_id, "-1");

				logs::add(twig::$lang['book_delete_log'] .' ('.$book_id.')', self::$log_setting);
				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&book_id='.$check_book_id->type);
			} else {
				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book');
			}
		} else {
			access_404();
		}
		exit;
	}




	// кастомные справочники
	static function custom_book() {
		self::check_permission();

		self::$main_title[] =  twig::$lang["book_custom"];

		$custom_book = book::get_type_book(1);
		twig::assign('custom_book', $custom_book);

		if(isset($_REQUEST['book_id'])) {
			$book_id = (int)$_REQUEST['book_id'];
			$book_info = $custom_book[$book_id];
			twig::assign('book_info', $book_info);

			/*if($book_info) {
				$fields_list = fields::get_custom_fields($book_id);
				twig::assign('fields_list', $fields_list);

				// типы полей
				twig::assign('field_custom_types', fields::get_fields());
			}*/
		}

		twig::assign('content', twig::fetch('book/custom_book.tpl'));
	}
	// кастомные справочники

	// новый справочник
	static function custom_book_add() {
		self::check_permission();

		$title = clear_text($_REQUEST['title']);
        $go_book = "";
		if(mb_strlen($title)>=config::app('app_min_strlen')) {
			DB::query("
				INSERT INTO " . DB::$db_prefix . "_book_custom
				SET
					title = '".$title . "'
			");
			$book_id = DB::lastInsertId();

			logs::add(twig::$lang['custom_book_add_log'] .' ('.$book_id.')', self::$log_setting);
			$go_book = '&book_id='.$book_id;
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book'.$go_book);
		exit;
	}
	// новый справочник

	// редактирвоание справочника
	static function custom_book_edit() {
		self::check_permission();

		$custom_book = book::get_type_book(1);
		$book_id = (int)$_REQUEST['book_id'];
		$book_info = $custom_book[$book_id];

		if($book_info) {

			// общие настройки
			$title = clear_text($_REQUEST['title']);
			$html_block = clear_tags(addslashes($_REQUEST['html_block']));
			$html_item = clear_tags(addslashes($_REQUEST['html_item']));
			if (
				mb_strlen($title)>=config::app('app_min_strlen') &&
				array_key_exists($book_id, $custom_book)
			) {
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_book_custom
					SET
						title = '".$title . "',
						html_block = '".$html_block . "',
						html_item = '".$html_item . "'
					WHERE
						id = '".$book_id."'
				");
			}
			// общие настройки

			logs::add(twig::$lang['custom_book_edit_log'] .' ('.$book_id.')', self::$log_setting);
			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book&book_id='.(int)$_REQUEST['book_id']);
			exit;
		} else {
			access_404();
		}
	}
	// редактирвоание справочника

	// удаление кастомного справочника
	static function custom_book_delete() {
		self::check_permission();

		$book_id = (int)$_REQUEST['book_id'];
		$check_book_id = DB::row("
			SELECT * FROM " . DB::$db_prefix . "_book_custom WHERE id = '".$book_id."' AND type = '1'
		");

		if($check_book_id) {
			DB::query("DELETE FROM " . DB::$db_prefix . "_book_custom WHERE id = '".$book_id."' ");

			$sql = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_fields
				WHERE field_item = '".(int)$book_id."'
			");
			foreach ($sql as $row) {
				DB::query("
					DELETE FROM " . DB::$db_prefix . "_fields_item WHERE item_type = '".$row->field_id."'
				");
			}

			DB::query("DELETE FROM " . DB::$db_prefix . "_fields WHERE field_item = '".(int)$book_id."' ");
			DB::query("DELETE FROM " . DB::$db_prefix . "_book_item WHERE book_id = '".(int)$book_id."' ");

			logs::add(twig::$lang["custom_book_delete_log"] .' (' . $book_id . ')', self::$log_setting);

			header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book');
			exit;
		} else {
			access_404();
		}
	}
	// удаление кастомного справочника

	// удаление поля
	static function book_fields_delete() {
		self::check_permission();

		$field_id = (int)$_REQUEST['field_id'];
		$book_id = (int)$_REQUEST['book_id'];

		$check_field_id = DB::row("
			SELECT * FROM " . DB::$db_prefix . "_fields WHERE field_id = '".$field_id."' AND field_item = '".$book_id."'
		");
		if($check_field_id) {
			fields::fields_delete_save();

			logs::add(twig::$lang['custom_book_field_delete_log'] . " (".$book_id.")", self::$log_setting);
		}

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book&book_id='.$check_field_id->field_item);
		exit;
	}
	// удаление поля

	// заполняем справочник
	static function custom_book_open() {
		self::check_permission();

		self::$main_title[] =  twig::$lang["custom_book_open"];

		$custom_book = self::get_type_book(1);
		twig::assign('custom_book', $custom_book);

		$book_id = (int)$_REQUEST['book_id'];
		$book_info = $custom_book[$book_id];

		if($book_info) {
			if(isset($_REQUEST['save'])) {
				$title = strip_tags(addslashes($_REQUEST['title']));
				if(mb_strlen($title)>=config::app('app_min_strlen')) {
					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_book_item
						SET
							title    = '" . $title . "',
							book_id    = '" . $book_id . "'
					");
					$item_id = DB::lastInsertId();

					// добавление нового поля для всех сущностей
					fields::create_custom_fields($book_id, $item_id);
					// добавление нового поля для всех сущностей
				}

				logs::add(twig::$lang["custom_book_item_add_log"] .' (' . $book_id . ')', self::$log_setting);
				header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book_item_edit&book_id='.$book_id.'&item_id='.$item_id);
				exit;
			}

			// собираем все содержимое справочника
			// pager
			$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
			// pager

			$sql_link = "";
			$page_link = "";
			$custom_book_search = clear_text($_REQUEST['custom_book_search']);
			if(mb_strlen($custom_book_search) >= config::app('app_min_strlen')) {
				$sql_link .= " AND title LIKE '%".$custom_book_search."%' ";
				$page_link .= "&custom_book_search=".urlencode($custom_book_search);
			}

			// pager
			$num = DB::numrows("
				SELECT
				id
				FROM " . DB::$db_prefix . "_book_item
				WHERE book_id = '".$book_id."' $sql_link
			");
			twig::assign('num', $num);
			if ($num > config::app('SYS_PEAR_PAGE'))
			{
				$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH_ADMIN_LINK. '?do=book&sub=custom_book_open'.$page_link.'&book_id='.$book_id.'&page={s}">{t}</a>');
				twig::assign('page_nav', $page_nav);
			}
			// pager

			$sql = DB::fetchrow("
				SELECT
				*
				FROM " . DB::$db_prefix . "_book_item
				WHERE book_id = '".$book_id."' $sql_link
				ORDER BY id DESC
				LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE')
			);
            $book_item = [];
			foreach ($sql as $row) {

				$row->field_show_item = self::custom_book_get_active_field($row->id);

				$book_item[$row->id] = $row;
			}

			twig::assign('book_item', $book_item);
			// собираем все содержимое справочника

			twig::assign('book_info', $book_info);
			twig::assign('content', twig::fetch('book/custom_book_open.tpl'));
		} else {
			access_404();
		}
	}
	// заполняем справочник

	static function custom_book_get_active_field($item_essence, $clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

		return DB::fetchrow("
			SELECT
				fie.field_title, fie_val.item_value
			FROM " . DB::$db_prefix . "_fields as fie
			JOIN " . DB::$db_prefix . "_fields_item as fie_val on fie_val.item_type = fie.field_id
			WHERE fie.field_show = '1' AND item_essence = '".$item_essence."'
		", $clear_cache);
	}

	// удаление позиции в справочнике
	static function custom_book_item_delete() {
		self::check_permission();

		$item_id = (int)$_REQUEST['item_id'];
		$book_id = (int)$_REQUEST['book_id'];

		DB::query("DELETE FROM " . DB::$db_prefix . "_book_item WHERE id = '".$item_id."' ");
		fields::delete_custom_field_value($item_id, $book_id);

		logs::add(twig::$lang["custom_book_item_delete_log"] .' (' . $book_id . ')', self::$log_setting);
		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=book&sub=custom_book_open&book_id='.$book_id);
		exit;
	}
	// удаление позиции в справочнике

	// редактирование позиции в справочнике
	static function custom_book_item_edit() {
		self::check_permission();

        $custom_book = self::get_type_book(1);
        twig::assign('custom_book', $custom_book);

		$item_id = (int)$_REQUEST['item_id'];
        $book_id = (int)$_REQUEST['book_id'];
        $book_info = $custom_book[$book_id];
		$error = array();

		$item_info = DB::row("
			SELECT
			*
			FROM " . DB::$db_prefix . "_book_item
			WHERE book_id = '".$book_id."' AND id = '".$item_id."'
		");

		if($book_info && $item_info) {
			if(isset($_REQUEST['save'])) {

				$title = strip_tags(addslashes($_REQUEST['title']));
				if(mb_strlen($title)>=config::app('app_min_strlen')) {
					DB::query("
						UPDATE
							" . DB::$db_prefix . "_book_item
						SET
							title    = '" . $title . "'
						WHERE
							book_id = '".$book_id."' AND id = '".$item_id."'
					");

					// field save
					$custom_field = $_REQUEST['custom_field'];
					$field_required = fields::save_custom_fields($item_id, $book_id, $custom_field);
					if($field_required) {
						$error = array_merge($error, $field_required);
					}
					// field save
				} else {
					$error[] = twig::$lang["custom_book_item_save_error"];
				}

				if($error) {
					twig::assign('error', $error);
					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}

				self::custom_book_get_active_field($item_id, "-1");

				logs::add(twig::$lang["custom_book_item_edit_log"] .' (' . $item_id . ')', self::$log_setting);
				echo json_encode(array("respons"=>twig::$lang["form_save_success"], "status"=>"success"));
				exit;
			}

			/* получение кастомных полей */
			fields::bild_custom_fields($book_id, $item_id);
			/* получение кастомных полей */

			twig::assign('item_info', $item_info);
			twig::assign('book_info', $book_info);
			twig::assign('content', twig::fetch('book/custom_book_item_edit.tpl'));
		} else {
			access_404();
		}
	}
	// редактирование позиции в справочнике



	// выборка содержимого справочника
	static function get_custom_book_item_by_name($book_id) {
		$sql = DB::fetchrow("
			SELECT *
			FROM " . DB::$db_prefix . "_book_item
			WHERE book_id = '".$book_id."'
		");
        $book_list = [];
		foreach ($sql as $row) {
			$book_list[$row->id] = $row->title;
		}

		return $book_list;
	}
	// выборка содержимого справочника


	// выборка из справочника
	static function book_search() {
		$search = clear_text($_REQUEST['query']);
    	$suggestions = array();

		if(mb_strlen($search)<config::app('app_min_strlen')) {
    		return false;
    	}

		$field_default = DB::single("
			SELECT field_default
			FROM " . DB::$db_prefix . "_fields
			WHERE
				field_id = '".(int)$_REQUEST['field_id']."'
		");

		if($field_default) {
	    	$sql = DB::fetchrow("
				SELECT itm.*
				FROM " . DB::$db_prefix . "_book_custom as bo
				JOIN " . DB::$db_prefix . "_book_item as itm on bo.id=itm.book_id
				WHERE
					itm.title LIKE '%".$search."%' AND bo.type = '1' AND book_id = '".(int)$field_default."'
			");
			foreach ($sql as $row) {
				$suggestions[] = array("value"=>$row->title,"data"=>$row->id);
			}

			$respon = array(
				'query'=>$search,
				'suggestions'=>$suggestions,
			);

			echo json_encode($respon);
		}
		exit;
	}
	// выборка из справочника

	// оформление выборки из справочника
	static function book_show_custom_block($item_id='', $value) {
		$item = $item_id ? $item_id : (int)$_REQUEST['item_id'];

		if($item) {
			$item_info = DB::row("
				SELECT itm.*, bo.html_block, bo.html_item
				FROM " . DB::$db_prefix . "_book_custom as bo
				JOIN " . DB::$db_prefix . "_book_item as itm on bo.id=itm.book_id
				WHERE
					bo.id = '".$item."' AND bo.type = '1'
			");

			if($item_info) {
				if(is_array($value)) {
					$value = implode("", $value);
				} else {
					$value = "";
				}

				$html_block	= str_replace('[content]', $value, stripslashes($item_info->html_block));

				if(backend::isAjax()) {
					$respon = array(
						'html'=>$html_block,
					);

					echo json_encode($respon);
					exit;
				} else {
					return $html_block;
				}
			}
		}
        return false;
	}
	static function book_show_custom_item($item_id='') {
		$item = $item_id ? $item_id : (int)$_REQUEST['item_id'];

		if($item) {
			$item_info = DB::row("
				SELECT itm.*, bo.html_block, bo.html_item
				FROM " . DB::$db_prefix . "_book_custom as bo
				JOIN " . DB::$db_prefix . "_book_item as itm on bo.id=itm.book_id
				WHERE
					itm.id = '".$item."' AND bo.type = '1'
			");

			if($item_info) {
				$item_fields = fields::get_custom_fields_value($item_info->book_id, $item);

				$html_item = str_replace('[title]', $item_info->title, stripslashes($item_info->html_item));
				//dd($item_fields);
				foreach ($item_fields as $key => $value) {

					$value->item_value_decode = json_decode($value->item_value);
					if(is_array($value->item_value_decode)) {
						$value->item_value = implode(", ", $value->item_value_decode);
					}

					$html_item = str_replace('[field]['.$key.']', $value->item_value, $html_item);
					$html_item = str_replace('[item_id]', $item, $html_item);
				}

				if(backend::isAjax()) {
					$respon = array(
						'html'=>$html_item,
					);

					echo json_encode($respon);
					exit;
				} else {
					return $html_item;
				}
			}
		}
        return false;
	}
	// оформление выборки из справочника
}
?>
