<?php
$lang = [
    'page_name' => 'Документы',
    'page_name_short' => 'Документы',
    'page_description' => 'Управление документами',

    'page_add' => 'Добавить документ',
    'page_edit' => 'Редактировать документ',
    'page_folder' => 'Управление папками',

    'page_title' => 'Название',
    'page_title_desc' => 'Начните печатать название для поиска',
    'page_meta_description' => 'Мета описание',
    'page_meta_keywords' => 'Мета ключевые слова',
    'page_meta_robots' => 'Мета индексация',
    'page_alias' => 'Алиас',
    'page_lang' => 'Язык',
    'page_tags' => 'Теги',
    'page_index' => 'Индексная страница сайта по умолчанию для языковой версии',
    'page_landing' => 'Лендинг',
    'page_landing_in_site' => 'Вывод в шаблоне сайта (для лендинга)',
    'page_text' => 'Содержание документа',
    'page_preview' => 'Превью',
    'page_reg' => 'Добавлен',
    'page_reg_edit' => 'Последнее изменение',
	'page_folder_name' => 'Папка',
    'page_owner' => 'Автор',
    'page_status' => 'Статус',
    'page_youtube' => 'Ссылка на youtube',

    'page_status_array' => [
        '1'=>'Активен',
        '2'=>'В архиве',
    ],
    'page_meta_robots_array' => [
        'index,follow',
		'index,nofollow',
		'noindex,nofollow'
    ],

    'page_landing_abs_path' => 'Путь к папке со стилями',
    'page_landing_abs_link' => 'Относительный путь для ссылок',

    'page_delete' => 'Удалить',
    'page_status_active' => 'Смена статуса (Активный)',
    'page_status_no_active' => 'Смена статуса (В архиве)',
    'page_change_folder' => 'Перенести в папку',

    'page_folder_title' => 'Название папки',
    'page_folder_add' => 'Добавить',

    'page_log_delete' => 'Удалил документ',
    'page_log_add' => 'Добавил документ',
    'page_log_edit' => 'Отредактировал документ',
    'page_log_add_folder' => 'Добавил папку с документами',
    'page_log_edit_folder' => 'Отредактировал папки с документами',
    'page_log_delete_folder' => 'Удалил папку для документов',

    'page_error_title' => 'Название короче '.config::app('app_min_strlen').'-х символов',
    'page_error_url_double' => 'Такой алиас уже есть в системе',
    'page_error_url' => 'Алиас слишком короткий',
    'page_error_folder' => 'Папка для документа указана неверно',
    'page_error_lang' => 'Такой язык не найден в системе',
    'page_error_robot' => 'Параметры индексации указаны неверно',
    'page_error_place' => 'Положение меню навигации указано неверно',
    'page_error_place_copy' => 'Для данного положения и языка уже есть меню навигации',

    'page_navi' => 'Меню навигации',
    'page_navi_add' => 'Добавить пункт',
    'page_navi_edit' => 'Редактировать пункт',
    'page_navi_place' => 'Расположение',
    'page_navi_log_add' => 'Добавил меню навигации',
    'page_navi_log_edit' => 'Отредактировал меню навигации',
    'page_navi_log_delete' => 'Удалил меню навигации',

    'page_navi_item_title' => 'Название пункта меню',
    'page_navi_page_title' => 'Название документа меню',

    'page_photos' => 'Набор фотографий',
];
?>
