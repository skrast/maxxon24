<?php
class site {
	static $log_setting =  4;
	static $main_title =  array();
	static $lang = "";
	static $link_lang_pref = "";
	static $page_info = "";

	function __construct() {
		self::$lang = config::app("app_lang");
	}

	static function bild_front() {

		if(!empty($_REQUEST['set_lang']) && in_array($_REQUEST['set_lang'], config::get_lang())) {
			$_SESSION['user_lang'] = $_REQUEST['set_lang'];
		} else {
		    $_SESSION['user_lang'] = config::app("app_lang");
		}

		// сохранение меток
		$referer = false;
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$referer = parse_url($_SERVER['HTTP_REFERER']);
			$referer = (trim($referer['host']) === $_SERVER['SERVER_NAME']);
		}
		// Если не наш REFERER или изменился IP-адрес
		// сверяем данные сессии с данными базы данных
		if (($referer === false || $_SESSION['user_ip'] != $_SERVER['REMOTE_ADDR']) && !$_SESSION['meta'] && $_GET)
		{
			$_SESSION['meta'] = $_GET;
		}
		// сохранение меток

		// доступные языковые версии
		$lang_array_array = [];
		foreach (config::get_lang() as $lang) {
			$lang_array[] = ($lang == config::app("app_lang")) ? "" : $lang;
		}
		\twig::assign('lang_array', $lang_array);
		\twig::assign('app_langs', config::app("app_langs"));
		// доступные языковые версии

		// подключаем меню навигации
		foreach (config::app('site_menu_place') as $key=>$value) {
			self::bild_navi($key);
		}
		// подключаем меню навигации

		// мелкие шаблоны для основного
		twig::assign('bild_login_tpl', twig::fetch('frontend/chank/bild_login_tpl.tpl'));

		// мелкие шаблоны для основного

		$do = (isset($_REQUEST['do'])) ? $_REQUEST['do'] : '';
		$sub = (isset($_REQUEST['sub'])) ? $_REQUEST['sub'] : '';
		if(class_exists($do)) {
		    if(method_exists($do, $sub)) {
		        $do::$sub();
				// title
				if(property_exists($do, 'main_title')) {
					self::bild_title($do::$main_title);
				}
				// title
		    }
		} else {
			self::open_page();

			// title
			if(property_exists("page", 'main_title')) {
				self::bild_title(self::$main_title);
			}
			// title
		}

		// PROFILING
		if (config::app('app_profiling') && $_SESSION['alles']) {
		    twig::assign('get_statistic', DB::get_statistic());
		}
		// PROFILING

		// шаблон сайта
		$disp = "frontend";

		// тип страницы
		if(self::$page_info->page_landing) {
			$disp = "landing";
			$source = stripslashes(self::$page_info->page_landing_sourse);
			$source = str_replace('[abs_path]', ABS_PATH.'assets/custom/', $source);
			$source = str_replace('[abs_link]', ABS_PATH, $source);

			if(self::$page_info->page_landing_in_site) {
				$disp = "frontend_extended";
			}

			twig::assign('content', $source);
		}

		// тикеты
		$tiket_group = modules\tiket::get_tiket_group();
		twig::assign('tiket_group', $tiket_group);
		// тикеты

		// шаблон сайта
		//siteHelper::get_partner_page();
		twig::display('frontend/'.$disp.'.tpl');
    }

	static function bild_title($title) {
		if(is_array($title)) {
			$title = array_unique($title);
			$page_title = implode(". ", array_reverse($title));
			$title['page_meta_title'] = $page_title;
		}

		twig::assign('meta', $title);
	}

	static function bild_navi($navi_place, $clear_cache='') {
		$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

		$parse_url = self::url_parse_helper($_SERVER['REQUEST_URI']);
		$get_url = $parse_url['page_url'];
		$page_lang = $parse_url['page_lang'] ? $parse_url['page_lang'] : config::app("app_lang");

		$navi_info = \DB::row("
			SELECT * FROM
				" . \DB::$db_prefix . "_module_page_navi
			WHERE navi_place = '".(int)$navi_place."' AND navi_lang = '".addslashes($page_lang)."'
		", $clear_cache);

		$navi_info->navi_items = modules\page::get_child_navi_item($navi_info->navi_id);

		twig::assign('navi_'.$navi_place, $navi_info);
	}


	// поиск страницы для основы сайта
	static function open_page() {
		$url_info = self::url_parse_helper();
		$page_info = self::url_parse($url_info);

		$tpl = 'open_page.tpl'; // шаблон по умолчанию для страниц

		if (!$page_info) {
			self::error404();
		} else {

			self::$main_title = $page_info;
			twig::assign('page_info', $page_info);
			twig::assign('current_lang', self::$lang);

			// конструктор адресов с учетом языка
			$link_lang_pref = self::$link_lang_pref = (self::$lang == config::app('app_lang')) ? "" : self::$lang;
			twig::assign('link_lang_pref', $link_lang_pref);
			// конструктор адресов с учетом языка

			// доп параметры для страниц
			switch (self::get_current_document_id()) {
				case '1':

				break;
				case '31':
					siteHelper::tarif_page();
					$tpl = 'open_page_tarif.tpl';
				break;

				default:

				break;
			}
			// доп параметры для страниц

			// доп параметры для разделов
			switch ($page_info->page_folder->folder_id) {
				case '2':
					siteHelper::get_photos_list($page_info->page_id);
					$tpl = 'open_page_news.tpl';
				break;

				case '4':
					siteHelper::get_news_list();
					$tpl = 'open_page_news_list.tpl';
				break;

				case '7':
					siteHelper::get_doc_list();
					$tpl = 'open_page_doc_list.tpl';
				break;
				case '6':
					siteHelper::get_photos_list($page_info->page_id);
					$tpl = 'open_page_doc.tpl';
				break;

				default:

				break;
			}
			// доп параметры для разделов

			// проверяем шаблон
			$file = BASE_DIR . '/assets/templates/frontend/open_page_'.$page_info->page_id.'.tpl';
			if(file_exists($file)) {
				$tpl = 'open_page_'.$page_info->page_id.'.tpl';
			}

			// специфические настройки для входных страниц языковых версий
			if($page_info->page_index == 1) {
				siteHelper::get_user_index();
				siteHelper::get_news_index();
				siteHelper::get_filter_index();

				if($_SESSION['alles']) {
					$tpl = 'open_index.tpl';
				} else {
					$tpl = 'zaglushka.tpl';
				}
				$tpl = 'open_index.tpl';
			}
			// специфические настрйоки для входных страниц языковых версий

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/'.$tpl));

			// увеличиваем счетчик просмотров (1 раз в пределах сессии)
			if (! isset ($_SESSION['page_view'][$page_info->page_id]))
			{
				DB::row("
					UPDATE
						" . DB::$db_prefix . "_module_page
					SET
						page_view = page_view + 1
					WHERE
						page_id = '" . $page_info->page_id . "'
				");

				$_SESSION['page_view'][$page_info->page_id] = time();
			}
			// увеличиваем счетчик просмотров (1 раз в пределах сессии)
		}

	}
	// поиск страницы для основы сайта

	static function error404() {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true);
		$page_info = self::_page_find(2);
		$_REQUEST['id'] = 2;

		$do = (isset($_REQUEST['do'])) ? $_REQUEST['do'] : '';
		$sub = (isset($_REQUEST['sub'])) ? $_REQUEST['sub'] : '';
		if(class_exists($do)) {
		    if(method_exists($do, $sub)) {
				// title
				if(property_exists($do, 'main_title')) {
					$do::$main_title =  $page_info;
				}
				// title
		    }
		}
		
		self::$main_title =  $page_info;

		twig::assign('page_info', $page_info);
		twig::assign('content', twig::fetch('frontend/open_404.tpl'));
	}

	static function get_current_document_id()
	{
		$_REQUEST['id'] = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? $_REQUEST['id'] : 1;

		return $_REQUEST['id'];
	}

	static function _page_find($page_id = 1)
	{
		$page_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_module_page
			WHERE page_id = '".$page_id."'
		");

		return $page_info;
	}

	static function url_parse_helper() {
		$get_url = $_SERVER['REQUEST_URI'];
		$get_url = trim($get_url, '/');

		// доступные языковые версии
		$lang_array_array = [];
		foreach (config::get_lang() as $lang) {
			$lang_array[] = $lang;
		}
		// доступные языковые версии

		$explode = explode("/", $get_url);
		$check_lang = array_shift($explode);
		$page_lang = config::app('app_lang');
		if(in_array($check_lang, $lang_array) && mb_strlen($check_lang) == 2) {
			$page_lang = $check_lang;
			$get_url = implode("/", $explode);
		}
		self::$lang = $page_lang;

		return ['page_url' => $get_url, 'page_lang' => $page_lang];
	}

	static function url_parse($parse_url = '')
	{
		$get_url = $parse_url['page_url'];
		$page_lang = $parse_url['page_lang'];

		// Если нужны параметры GET, можно отключить
		$get_url = (strpos($get_url, ABS_PATH . '?') === 0 ? "" : $get_url);

		if	(
			substr($get_url,0,strlen(ABS_PATH.'index.php'))!=ABS_PATH.'index.php'&&strpos($get_url,'?')!==false
			) $get_url=substr($get_url,0,strpos($get_url,'?'));

		$get_url = rawurldecode($get_url);
		//$get_url = mb_substr($get_url, strlen(ABS_PATH));
		$test_url = $get_url; // сохранение старого урла для првоерки использования суффикса

		if (mb_substr($get_url, - strlen(config::app("URL_SUFF"))) == config::app("URL_SUFF"))
		{
			$get_url = mb_substr($get_url, 0, - strlen(config::app("URL_SUFF")));
		}

		// Разбиваем строку пароаметров на отдельные части
		$get_url = explode('/', $get_url);

		if (isset ($get_url['index']))
		{
			unset ($get_url['index']);
		}

		if (isset ($get_url['print']))
		{
			$_GET['print'] = $_REQUEST['print'] = 1;
			unset ($get_url['print']);
		}

		$get_url = implode('/', $get_url);

		// Выполняем запрос к БД на получение всей необходимой
		// информации о документе
		$page_info = DB::row("
			SELECT
				*
			FROM
				" . DB::$db_prefix . "_module_page
			WHERE
				page_alias = '" . (!empty ($get_url) ? addslashes($get_url) : "/") . "'
				AND page_status = '1'
			LIMIT 1
		");
		//".($page_lang ? " AND page_lang = '".addslashes($page_lang)."' " : "")."
		if ($page_info)	{
			$_GET['id']  = $_REQUEST['id']  = $page_info->page_id;

			// перенаправление на адреса с суффиксом
			if ($test_url !== $get_url . config::app("URL_SUFF") && !$pages && $test_url && !$_REQUEST['print'])
			{
				header('HTTP/1.1 301 Moved Permanently');
				if (self::get_current_document_id() == 1)
				{
					header('Location:' . ABS_PATH);
					exit();
				}
				else
				{
					header('Location:' . ABS_PATH . $get_url . config::app("URL_SUFF"));
					exit();
				}
			}

			$page_info = modules\page::page_info($page_info);
			self::$page_info = $page_info;

			return $page_info;
		} else {
			$_GET['id'] = $_REQUEST['id'] = 2;
			return false;
		}
	}
}

?>
