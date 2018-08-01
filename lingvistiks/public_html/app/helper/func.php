<?php
function microtime_diff($a="", $b="") {
	list($a_dec, $a_sec) = explode(' ', $a);
	list($b_dec, $b_sec) = explode(' ', $b);
	return $b_sec - $a_sec + $b_dec - $a_dec;
}

function country_by_lang($clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$user_lang = config::get_user_lang($_SESSION['user_lang']);

	$sql = DB::fetchrow("
		SELECT * FROM
			" . DB::$db_prefix . "_module_country
		ORDER BY country_id ASC
	", $clear_cache);
	$country_list = [];
	foreach ($sql as $row) {
		$row->country_title_short = unserialize($row->country_title_short);
		$row->country_title_full = unserialize($row->country_title_full);
		$row->country_lang = unserialize($row->country_lang);

		$country_list[$row->country_title_short[$user_lang]] = [
			"id"=>$row->country_id,
			"title"=>$row->country_title_short[$user_lang],
		];
	}

	ksort($city_list);
	$temp = $country_list;

	/*usort($country_list, function($a, $b){
		return strnatcmp($a['title'], $b['title']);
	});

	$temp = [];
	foreach ($country_list as $value) {
		$temp[$value['title']] = $value;
	}*/

	$country_list = [];
	foreach($temp as $te) {
		$country_list[$te['id']] = $te;
	}

	return $country_list;
}

function city_by_lang($country, $clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$user_lang = config::get_user_lang($_SESSION['user_lang']);

	$sql = DB::fetchrow("
		SELECT * FROM
			" . DB::$db_prefix . "_module_city
		WHERE city_country = '".(int)$country."'
		ORDER BY city_id ASC
	", $clear_cache);

	$city_list = [];
	foreach ($sql as $row) {
		$row->city_title = unserialize($row->city_title);

		$city_list[$row->city_title[$user_lang]] = [
			"id"=>$row->city_id,
			"title"=>$row->city_title[$user_lang],
			"utm"=>$row->city_utm,
		];
	}

	ksort($city_list);
	$temp = $city_list;

	/*usort($city_list, function($a, $b){
		return strnatcmp($a['title'], $b['title']);
	});

	$temp = [];
	foreach ($city_list as $value) {
		$temp[$value['title']] = $value;
	}*/

	$city_list = [];
	foreach($temp as $te) {
		$city_list[$te['id']] = $te;
	}

	return $city_list;
}

function metro_by_lang($city, $clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$user_lang = config::get_user_lang($_SESSION['user_lang']);

	$sql = DB::fetchrow("
		SELECT * FROM
			" . DB::$db_prefix . "_module_metro
		WHERE metro_city = '".(int)$city."'
		ORDER BY metro_id ASC
	", $clear_cache);

	$metro_list = [];
	foreach ($sql as $row) {
		$row->metro_title = unserialize($row->metro_title);

		$metro_list[$row->metro_title[$user_lang]] = [
			"id"=>$row->metro_id,
			"title"=>$row->metro_title[$user_lang],
		];
	}

	ksort($metro_list);
	$temp = $metro_list;

	/*usort($metro_list, function($a, $b){
		return strnatcmp($a['title'], $b['title']);
	});*/

	/*$temp = [];
	foreach ($metro_list as $value) {
		$temp[$value['title']] = $value;
	}*/

	$metro_list = [];
	foreach($temp as $te) {
		$metro_list[$te['id']] = $te;
	}

	return $metro_list;
}

function get_country_by_lang($lang) {
	//$lang = 0; // russian
	$headerOptions = array(
		'http' => array(
			'method' => "GET",
			'header' => "Accept-language: ".$lang."\r\n"
		)
	);
	$methodUrl = 'http://api.vk.com/method/database.getCountries?v=5.5&need_all=1&count=1000&lang='.$lang;
	$streamContext = stream_context_create($headerOptions);
	$json = file_get_contents($methodUrl, false, $streamContext);
	$arr = json_decode($json, true);
	$array_list = [];
	foreach ($arr['response']['items'] as $key => $value) {
		$array_list[$value['id']] = $value;
	}
	return $array_list;
}

function get_city_by_lang($country, $lang) {
	//$lang = 0; // russian
	$headerOptions = array(
		'http' => array(
			'method' => "GET",
			'header' => "Accept-language: ".$lang."\r\n"
		)
	);
	$methodUrl = 'http://api.vk.com/method/database.getCities?v=5.5&&offset=0&need_all=0&count=1000&count=1000&country_id='.$country.'&lang='.$lang;
	$streamContext = stream_context_create($headerOptions);
	$json = file_get_contents($methodUrl, false, $streamContext);
	$arr = json_decode($json, true);
	$array_list = [];
	foreach ($arr['response']['items'] as $key => $value) {
		$array_list[$value['id']] = $value;
	}
	return $array_list;
}


// для работы с exel файлами
function excel2mysql($worksheet) {
	$value = array();
	$rows = array();

	$highestRow = $worksheet->getHighestRow();
	$highestColumn = $worksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	for ($row = 1; $row <= $highestRow; ++$row) {
	  for ($col = 0; $col <= $highestColumnIndex; ++$col) {
	    $rows[$col] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
	  }
	  array_push($value, $rows);
	}

	return $value;
}
// для работы с exel файлами


function dd($text, $close=true) {
	if(is_array($text)) {
		echo count($text)."<br>";
	}

	print_r("<pre>");
	print_r($text);
	print_r("</pre>");
	if($close === true) exit;
}

function clear_tags($text) {
	$text = htmlspecialchars_decode($text);
	return strip_tags(trim($text), '<br><b><u><strong><em><p><i><a><img><table><tbody><thead><tr><td><th><li><ul><ol><span><div><button>');
}

function clear_phone_to_int($phone) {
	return preg_replace('/[^0-9]/', '', $phone);
	//return (int)(str_replace(array(" ","(",")","-"), "", $phone));
}

function clear_text($text) {
	return addslashes(strip_tags(trim($text)));
}

// 404 page
function access_404() {
	if(backend::isAjax() || backend::isOutside()) {
		echo json_encode(array("respons"=>twig::$lang["form_message_error"], "status"=>"error"));
		exit;
	}

	backend::bild_404();
}
// 404 page

// security ip
function get_security($clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$sql = DB::fetchrow("
		SELECT * FROM
			" . DB::$db_prefix . "_security
		ORDER BY id ASC
	", $clear_cache);
	foreach ($sql as $row) {
		$security[$row->id] = $row;
	}
	return $security;
}
// security ip

// work_user
function get_work_user($clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$sql = DB::fetchrow("
		SELECT *
		FROM " . DB::$db_prefix . "_users
		ORDER BY id ASC
	", $clear_cache);
	$main_users = [];
	foreach ($sql as $row) {
		$row = profile::profile_info($row);
		$main_users[$row->id] = $row;
	}
	return $main_users;
}
// work_user

// work_group
function get_work_group($clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$sql = DB::fetchrow("
		SELECT *
		FROM " . DB::$db_prefix . "_users_groups
		ORDER BY user_group ASC
	", $clear_cache);
	$main_group = [];
	foreach ($sql as $row) {
		$row->user_group_module = explode(",", $row->user_group_module);
		$main_group[$row->user_group] = $row;
	}
	return $main_group;
}
// work_group

// справочники
function get_book_for_essence($status_type = '', $clear_cache='') {
	$clear_cache = ($clear_cache) ? $clear_cache : config::app('CACHE_LIFETIME_LONG');

	$sql = DB::fetchrow("
		SELECT *
		FROM " . DB::$db_prefix . "_book
		ORDER BY sort ASC
	", $clear_cache);

	$book = [];
	$book_type = [];
	foreach ($sql as $row) {
		$row = book::book_info($row);

		if($status_type && $status_type == $row->type) {
			$book_type[$row->id] = $row;
		}
		$book[$row->id] = $row;
	}

	return (!empty($status_type)) ? $book_type : $book;
}
// справочники


// информация по пользователю основа
function get_user_info($user_id='') {

	$user_id = $user_id ? (int)$user_id : $_SESSION['user_id'];
	$user_info = DB::row("
		SELECT *
		FROM " . DB::$db_prefix . "_users as usr
		JOIN " . DB::$db_prefix . "_users_groups as gr on gr.user_group = usr.user_group
		WHERE id = '".$user_id."'
	");

	if($user_info) {
		$user_info = profile::profile_info($user_info);
		return $user_info;
	}

    return false;
}
// информация по пользователю основа



/* other */
function unix_to_date( $date )
{
	return ($date) ? date(config::app('app_date_format'), $date) : "";
}
function unix_to_date_time( $date )
{
	return ($date) ? date(config::app('app_date_format_time'), $date) : "";
}

function date_to_unix($data=0)
{
	$timestamp = "";
	if(!empty($data)) {
		$data = explode(" ", $data);
		$stamp['day'] = explode(".", $data[0]);
		if(!empty($data[1])) {
			$stamp['time'] = explode(":", $data[1]);
		} else {
			$stamp['time'] = explode(":", "01:00");
		}

		if (!empty($stamp)) {
			$timestamp = mktime(
				$stamp['time'][0],
				$stamp['time'][1],
				0,
				$stamp['day'][1],
				$stamp['day'][0],
				$stamp['day'][2]
			);
		}
		return $timestamp;
	}
	return false;
}
function date_to_unix_time($data=0)
{
	$timestamp = "";
	if(!empty($data)) {
		$data = explode(" ", $data);
		$stamp['day'] = explode(".", $data[0]);
		if(!empty($data[1])) {
			$stamp['time'] = explode(":", $data[1]);
		} else {
			$stamp['time'] = explode(":", "01:00");
		}

		if (!empty($stamp)) {
			$timestamp = mktime(
				$stamp['time'][0],
				$stamp['time'][1],
				0,
				$stamp['day'][1],
				$stamp['day'][0],
				$stamp['day'][2]
			);
		}
		return $timestamp;
	}
	return false;
}

// составляем правильные числительные
function declension($digit,$expr,$onlyword=false)
{
        if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i=preg_replace('/[^0-9]+/s','',$digit)%100; //intval не всегда корректно работает
        if($onlyword) $digit='';
        if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
        else
        {
                $i%=10;
                if($i==1) $res=$digit.' '.$expr[0];
                elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
                else $res=$digit.' '.$expr[2];
        }
        return trim($res);
}
// составляем правильные числительные

// проверяем что передано в виде изображения
function image_get_info($file) {
    if (!is_file($file)) {
        return FALSE;
    }

    $data = @getimagesize($file);
    $file_size = @filesize($file);

    if (isset($data) && is_array($data)) {
	    $extensions = config::app('app_allow_img');
		$tmp = explode('.', $file);
		$file_extension = end($tmp);

	    $extension = array_key_exists($file_extension, $extensions) ?  $file_extension : '';
	    $details = array(
			'width'     => $data[0],
			'height'    => $data[1],
			'extension' => $extension,
			'file_size' => $file_size,
			'mime_type' => $data['mime']
		);
	    return $details;
    }
    return false;
}

// проверяем что передано в виде другого файла
function file_get_info($file) {
	$mapping = config::app('app_allow_ext');
	foreach ($mapping as $ext_preg => $mime_match) {
		if (preg_match('!\.('. $ext_preg .')$!i', $file, $ext)) {
			$ext = explode('|',$ext_preg);
			$mime = explode('/',$mime_match);

			return $details = array(
				'extension'     => $ext['0'],
				'mime_type'    => $mime['0']
			);
	    }
	}
	return false;
}

// переименовываем файлы
function rename_file($file, $path) {
	$out = file_get_info($file); // проверяем что передано

	if ($out != false) {
		return bild_name($path, $out['extension']);
	}
    return false;
}
function bild_name($path, $extension) {
	$body = md5(microtime()); // получаем набор цифр
	$rn_file = 'f_' . $body.$_SESSION['user_id'].".".$extension; // собираем название файла
	if(file_exists($path . $rn_file)) bild_name($path, $extension); // если уже такой есть, всё заново
	return $rn_file;
}
// переименовываем файлы


// проверка правильности адреса
function check_url($string) {
	if(!empty($string)) {
		$string = stripslashes(strip_tags($string));

		if (!filter_var($string, FILTER_VALIDATE_URL)){
			return false;
		} else {
			$string = str_replace(array("http://", "http://www.", "www."), "http://", $string);
			$string = str_replace(array("https://", "https://www."), "https://", $string);
			$string = strtolower($string);
			return $string;
		}
	} else {
		return false;
	}
}


/**
 * Формирование строки из случайных символов
 *
 * @param int $length количество символов в строке
 * @param string $chars набор символов для формирования строки
 * @return string сформированная строка
 */
function make_random_string($length = 16, $chars = '')
{
	if ($chars == '')
	{
		$chars  = 'abcdefghijklmnopqrstuvwxyz';
		$chars .= 'ABCDEFGHIJKLMNOPRQSTUVWXYZ';
		$chars .= '~!@#$%^&*()-_=+{[;:/?.,]}';
		$chars .= '0123456789';
	}

	$clen = strlen($chars) - 1;

	$string = '';
	while (strlen($string) < $length) $string .= $chars[mt_rand(0, $clen)];

	return $string;
}

function format_string_number($string, $no_space=false) {
	$no_space = ($no_space) ? '' : ' ';
	$string = str_replace(",", ".", $string);
	$string = str_replace(" ", "", $string);
	return (is_numeric($string)) ? (number_format($string, config::app('app_float'), '.', $no_space)) : false;
}

function format_string_number_tranc($string) {
	return profile::add_zero_to_string($string, 6);
}

function get_home_link()
{
	return HOST;
}


// Формирование строки имени пользователя
function get_username($first_name = '', $last_name = '', $user_login = '')
{
	$first_name = trim($first_name);
	$last_name = trim($last_name);
	$user_login = trim($user_login);

	if($user_login) {
		return $user_login;
	} else {
		return 'NO NAME';
	}

	/*if ($first_name != '' && $last_name != '')
	{
		return ucfirst_utf8(mb_strtolower($first_name)) . ' ' . ucfirst_utf8(mb_strtolower($last_name));
	}
	elseif ($first_name != '' && $last_name == '')
	{
		return ucfirst_utf8(mb_strtolower($first_name));
	}
	elseif ($first_name == '' && $last_name != '')
	{
		return ucfirst_utf8(mb_strtolower($last_name));
	}
	return $user_login;*/
}
function ucfirst_utf8($str){
        $string = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
        return $string;
}
// Формирование строки имени пользователя

// преобразование текста
function text_to_link($text) {
	$text = preg_replace('/((www.|http:\/\/|http:\/\/www.|https:\/\/|https:\/\/www.)([\w\d\.\?\&\#\;\:\+\-\=\%\/]+))/i', '<a href="http://$3">$1</a>', $text);
	//$text = preg_replace('/(()([\w\d\.\?\&\#\;\:\+\-\=\%\/]+))/i', '<a href="$1">$1</a>', $text);
	//$text = preg_replace('/(www.([a-zA-Z_0-9\.\?\&\#\;\:\+\-\=\%\/]*))/i', '<a href="http://$1">$1</a>', $text);
	$text = preg_replace('/(ftp:\/\/([\w\d\.\?\&\#\;\:\+\-\=\%\/]+))/i', '<a href="$1">$1</a>', $text);
	$text = preg_replace('/([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i', '<a href="mailto:$1@$2">$1@$2</a>', $text);

	return($text);
}
// преобразование текста


function get_referer_link()
{
	static $link = null;

	if ($link === null)
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$link = parse_url($_SERVER['HTTP_REFERER']);
			$link = (trim($link['host']) == $_SERVER['SERVER_NAME']);
		}
		$link = ($link === true ? $_SERVER['HTTP_REFERER'] : get_home_link());
	}

	return $link;
}

// перевод размера файлов
function getSymbolByQuantity($size) {
	$mod = 1024;

	$units = explode(' ','B KB MB GB TB PB');
	for ($i = 0; $size > $mod; $i++) {
		$size /= $mod;
	}

	return round($size, 2) . ' ' . $units[$i];
}
// перевод размера файлов

// интервалы времени
function format_interval($timestamp, $granularity = 2) {
	$units = config::app('app_time_long');
	$output = "";
	foreach ($units as $key => $value) {
	    if ($timestamp >= $value) {
			$replace =  floor($timestamp / $value);
			$out = declension($replace, $key);
			$output .= ($output ? ' ' : '') . $out;
			$timestamp %= $value;
			$granularity--;
	    }
	    if ($granularity == 0) {
	      break;
	    }
  	}
  	return $output;
}
// интервалы времени

// пагинация
function get_current_page($type = 'page')
{

	if ($type != 'page' && $_REQUEST['page_type'] != $type) return 1;
	$page = (isset($_REQUEST[$type]) && is_numeric($_REQUEST[$type])) ? $_REQUEST[$type] : 1;
	return (int)$page;
}
/**
 * Постраничная навигация для запросов и модулей
 *
 * @param int $total_pages			количество страниц в документе
 * @param string $type				тип постраничной навигации,
 * 									допустимые значения: page, apage, artpage
 * @param string $template_label	шаблон метки навигации
 * @param string $navi_box			контейнер постраничной навигации
 * @return string					HTML-код постраничной навигации
 */
function get_pagination($total_pages, $type, $template_label)
{
	$nav = '';

	if ($type != 'page' && $_REQUEST['page_type'] != $type) $type = 'page';
	$curent_page = get_current_page($type);

	if     ($curent_page   == 1)			$seiten = array ($curent_page,   $curent_page+1, $curent_page+2, $curent_page+3, $curent_page+4);
	elseif ($curent_page   == 2)			$seiten = array ($curent_page-1, $curent_page,   $curent_page+1, $curent_page+2, $curent_page+3);
	elseif ($curent_page+1 == $total_pages)	$seiten = array ($curent_page-3, $curent_page-2, $curent_page-1, $curent_page,   $curent_page+1);
	elseif ($curent_page   == $total_pages)	$seiten = array ($curent_page-4, $curent_page-3, $curent_page-2, $curent_page-1, $curent_page);
	else									$seiten = array ($curent_page-2, $curent_page-1, $curent_page,   $curent_page+1, $curent_page+2);

	/*if($curent_page > $total_pages) {
		return '';
	}*/

	/*$final = [];
	$seiten = array_unique($seiten);
	foreach($seiten as $seiten_page) {
		if($total_pages <= $seiten_page) {
			$final[] = $seiten_page;
		}
	}
	$seiten = $final;*/

	$start_label     = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
	$end_label       = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
	$next_label      = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
	$prev_label      = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
	$navi_box      	 = "<div class='clearfix'></div><div class='text-center'><ul class='pagination pagination-sm'>%s</ul></div>";

	if ($total_pages > 5 && $curent_page > 3)
	{
        //Первая
        $nav .= '<li>'.str_replace('{t}', $start_label, str_replace(array('?'.$type.'={s}','&amp;'.$type.'={s}','&'.$type.'={s}','/'.$type.'-{s}'), '', $template_label)).'</li>';
	}

	if ($curent_page > 1)
	{
		if ($curent_page == 2)
		{
            //ХЗ
			$nav .= '<li>'.str_replace('{t}', $prev_label, str_replace(array('?'.$type.'={s}','&amp;'.$type.'={s}','&'.$type.'={s}','/'.$type.'-{s}'), '', $template_label)).'</li>';
		}
		else
		{
            //Предыдущая
			$nav .= '<li>'.str_replace('{t}', $prev_label, str_replace('{s}', ($curent_page - 1), $template_label)).'</li>';
		}
	}

	foreach($seiten as $val)
	{
		if ($val >= 1 && $val <= $total_pages)
		{
			if ($curent_page == $val)
			{
			    //Текущая
				$nav .= str_replace(array('{s}', '{t}'), $val, '<li class="active"><a>' . $curent_page . '</a></li>');
			}
			else
			{
				if ($val == 1)
				{

					$nav .= '<li>'.str_replace('{t}', $val, str_replace(array('?'.$type.'={s}','&amp;'.$type.'={s}','&'.$type.'={s}','/'.$type.'-{s}'), '', $template_label)).'</li>';
				}
				else
				{
                    //Остальные неактивные
					$nav .= '<li>'.str_replace(array('{s}', '{t}'), $val, $template_label).'</li>';
				}
			}
		}
	}

	if ($curent_page < $total_pages)
	{
        //Сдедующая
		$nav .= '<li>'.str_replace('{t}', $next_label, str_replace('{s}', ($curent_page + 1), $template_label)).'</li>';
	}

	if ($total_pages > 5 && ($curent_page < $total_pages-2))
	{
        //Последняя
		$nav .= '<li>'.str_replace('{t}', $end_label, str_replace('{s}', $total_pages, $template_label)).'</li>';
	}

	if ($nav != '')
	{
	    //Страница ХХХ из ХХХ
		$nav = sprintf($navi_box, $nav);
	}

	return $nav;
}
// пагинация

function write_htaccess_deny($dir)
{
	$file = $dir . '/.htaccess';
	if(!file_exists($file))
	{
		@file_put_contents($dir . '/.htaccess','Deny from all');
	}
}

/**
* Рекурсивно чистит директорию
*/
function rrmdir($dir, &$result = 0)
{
	if (is_dir($dir))
	{
		$objects = scandir($dir);

		foreach ($objects as $object)
		{
			if ($object != '.' && $object != '..')
			{
				if (filetype($dir . '/' . $object) == 'dir') rrmdir($dir . '/' . $object, $result);
				else $result = $result + (unlink($dir . '/' . $object) ? 0 : 1);
			}
		}

		reset($objects);

		$result = $result + (rmdir($dir) ? 0 : 1);
	}
	return $result > 0 ? false : true;
}

function create_file($file_path, $data='') {
	if(!file_exists($file_path))
	{
		file_put_contents($file_path, $data);
		chmod($file_path, 0777);
	}
}

function prepare_url($url)
{
	if($url == '/') return '';

	$new_url = strip_tags($url);

	$table = array(

// спецсимволы
				'«' => '',
				'»' => '',
				'—' => '',
				'–' => '',
				'“' => '',
				'”' => ''
	);
	$new_url = str_replace(array_keys($table),  array_values($table), $new_url);
	$new_url = translit_string(trim(_strtolower($new_url)));

	$new_url = preg_replace(
		array('/^[\/-]+|[\/-]+$|^[\/_]+|[\/_]+$|[^\.a-zа-яеёA-ZА-ЯЕЁ0-9\/_-]/u', '/--+/', '/-*\/+-*/', '/\/\/+/'),
		array('-',														  '-',	 '/',		 '/'),
		$new_url
	);
	$new_url = trim($new_url, '-');

	return mb_strtolower(rtrim($new_url,'.'),'UTF-8');
}
/**
 * Транслитерация
 *
 * @param string $string строка для транслитерации
 * @return string
 */
function translit_string($string)
{
//	$st = htmlspecialchars_decode($st);
//
//	// Convert all named HTML entities to numeric entities
//	$st = preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $st);
//
//	// Convert all numeric entities to their actual character
//	$st = preg_replace('/&#x([0-9a-f]{1,7});/ei', 'chr(hexdec("\\1"))', $st);
//	$st = preg_replace('/&#([0-9]{1,7});/e', 'chr("\\1")', $st);
//

	$table=Array(

				// Русский язык:
				'А' => 'A',
				'Б' => 'B',
				'В' => 'V',
				'Г' => 'G',
				'Д' => 'D',
				'Е' => 'E',
				'Ё' => 'YO',
				'Ж' => 'ZH',
				'З' => 'Z',
				'И' => 'I',
				'Й' => 'J',
				'К' => 'K',
				'Л' => 'L',
				'М' => 'M',
				'Н' => 'N',
				'О' => 'O',
				'П' => 'P',
				'Р' => 'R',
				'С' => 'S',
				'Т' => 'T',
				'У' => 'U',
				'Ф' => 'F',
				'Х' => 'H',
				'Ц' => 'C',
				'Ч' => 'CH',
				'Ш' => 'SH',
				'Щ' => 'CSH',
				'Ь' => '',
				'Ы' => 'Y',
				'Ъ' => '',
				'Э' => 'E',
				'Ю' => 'YU',
				'Я' => 'YA',

				'а' => 'a',
				'б' => 'b',
				'в' => 'v',
				'г' => 'g',
				'д' => 'd',
				'е' => 'e',
				'ё' => 'yo',
				'ж' => 'zh',
				'з' => 'z',
				'и' => 'i',
				'й' => 'j',
				'к' => 'k',
				'л' => 'l',
				'м' => 'm',
				'н' => 'n',
				'о' => 'o',
				'п' => 'p',
				'р' => 'r',
				'с' => 's',
				'т' => 't',
				'у' => 'u',
				'ф' => 'f',
				'х' => 'h',
				'ц' => 'c',
				'ч' => 'ch',
				'ш' => 'sh',
				'щ' => 'csh',
				'ь' => '',
				'ы' => 'y',
				'ъ' => '',
				'э' => 'e',
				'ю' => 'yu',
				'я' => 'ya',

				// українська мова:
				'і' => 'ya',
				'І' => 'ya',
				'ї' => 'ya',
				'Ї' => 'ya',
				'є' => 'ya',
				'Є' => 'ya',

				// polski język
				'Ą' => 'ya',
				'ą' => 'ya',
				'Ć' => 'ya',
				'ć' => 'ya',
				'Ę' => 'ya',
				'ę' => 'ya',
				'Ł' => 'ya',
				'ł' => 'ya',
				'Ń' => 'ya',
				'ń' => 'ya',
				'Ó' => 'ya',
				'ó' => 'ya',
				'Ś' => 'ya',
				'ś' => 'ya',
				'Ź' => 'ya',
				'ź' => 'ya',
				'Ż' => 'ya',
				'ż' => 'ya',

);
	$string = str_replace(array_keys($table),  array_values($table), $string);

	$string = strtr($string, array('ье'=>'ye', 'ъе'=>'ye', 'ьи'=>'yi',  'ъи'=>'yi',
							'ъо'=>'yo', 'ьо'=>'yo', 'ё'=>'yo',   'ю'=>'yu',
							'я'=>'ya',  'ж'=>'zh',  'х'=>'kh',   'ц'=>'ts',
							'ч'=>'ch',  'ш'=>'sh',  'щ'=>'shch', 'ъ'=>'',
							'ь'=>'',	'ї'=>'yi',  'є'=>'ye')
	);
	$string = strtr($string,'абвгдезийклмнопрстуфыэі',
					'abvgdeziyklmnoprstufyei');

	return trim($string, '-');
}
/**
 * Переводит кирилицу в нижний регистр
 *
 * @param string $string строка для перевода в нижний регистр
 * @return string
 */
function _strtolower($string)
{
	$small = array('а','б','в','г','д','е','ё','ж','з','и','й',
		'к','л','м','н','о','п','р','с','т','у','ф',
		'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
		'э', 'ю', 'я');
	$large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
		'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
		'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
		'Э', 'Ю', 'Я');
	return str_replace($large, $small, $string);
}


function number2string($number) {
	
	// обозначаем словарь в виде статической переменной функции, чтобы 
	// при повторном использовании функции его не определять заново
	$dic = [
	
		// словарь необходимых чисел
		twig::$lang['billing_pdf_dic_num'],
		
		// словарь порядков со склонениями для плюрализации
		twig::$lang['billing_pdf_dic_num_ext'],
		
		// карта плюрализации
		array(
			2, 0, 1, 1, 1, 2
		)
	];
	
	// обозначаем переменную в которую будем писать сгенерированный текст
	$string = array();
	
	// дополняем число нулями слева до количества цифр кратного трем,
	// например 1234, преобразуется в 001234
	$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
	
	// разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
	// т.к. мы не знаем максимальный порядок числа и будем бежать снизу
	// единицы, тысячи, миллионы и т.д.
	$parts = array_reverse(str_split($number,3));
	
	// бежим по каждой части
	foreach($parts as $i=>$part) {
		
		// если часть не равна нулю, нам надо преобразовать ее в текст
		if($part>0) {
			
			// обозначаем переменную в которую будем писать составные числа для текущей части
			$digits = array();
			
			// если число треххзначное, запоминаем количество сотен
			if($part>99) {
				$digits[] = floor($part/100)*100;
			}
			
			// если последние 2 цифры не равны нулю, продолжаем искать составные числа
			// (данный блок прокомментирую при необходимости)
			if($mod1=$part%100) {
				$mod2 = $part%10;
				$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
				if($mod1<20 || !$mod2) {
					$digits[] = $flag*$mod1;
				} else {
					$digits[] = floor($mod1/10)*10;
					$digits[] = $flag*$mod2;
				}
			}
			
			// берем последнее составное число, для плюрализации
			$last = abs(end($digits));
			
			// преобразуем все составные числа в слова
			foreach($digits as $j=>$digit) {
				$digits[$j] = $dic[0][$digit];
			}
			
			// добавляем обозначение порядка или валюту
			$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
			
			// объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
			array_unshift($string, join(' ', $digits));
		}
	}
	
	// преобразуем переменную в текст и возвращаем из функции, ура!
	return join(' ', $string);
}
?>
