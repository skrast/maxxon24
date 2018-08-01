<?php
$lang = [
    'sendmail_name'=>'Письма',
    'sendmail_name_one'=>'Письмо',

    'sendmail_add'=>'Новое письмо',
	'sendmail_edit'=>'Просмотр письма',
    'sendmail_error_mail'=>'Не указаны получатели письма или адреса в неверном формате',
    'sendmail_error_title_body'=>'Тема и сообщение должны быть длиннее '.config::app('app_min_strlen').'-х символов',
    'sendmail_save_draft'=>'Черновик сохранен',

    'sendmail_folder'=>'Папки',
    'sendmail_folder_all'=>'Вся почта',
    'sendmail_label'=>'Ярлыки',
    'sendmail_author'=>'Автор',
    'sendmail_theme'=>'Тема',
    'sendmail_delete'=>'Удалить',
    'sendmail_move_to_trash'=>'В корзину',
    'sendmail_erase_trash'=>'Очистить корзину',
    'sendmail_check_read'=>'Отметить прочитанными',
    'sendmail_check_no_read'=>'Отметить не прочитанными',
    'sendmail_stat'=>'Статистика',

    'sendmail_search_contact'=>'Поиск контактов',
    'sendmail_search_contact_exz'=>'test@domain.com',
    'sendmail_search_contact_dop'=>'Кому (Дополнительные адреса)',
    'sendmail_search_contact_dop_exz'=>'test@domain.com, test2@domain.com',
    'sendmail_mail_title'=>'Тема письма',
    'sendmail_mail_desc'=>'Сообщение',
    'sendmail_mail_template'=>'Шаблон сообщения',
    'sendmail_save_draft'=>'Сохранить как черновик (письмо не будет отправлено)',
    'sendmail_tracker'=>'Добавить код отслеживания',

    'sendmail_open_owner'=>'Отправил: ',
    'sendmail_open_owner_default'=>'Почтовый робот',
    'sendmail_open_target'=>'Получатели: ',

    'sendmail_open_stat'=>'Статистика письма',
    'sendmail_open_stat_day'=>'По дням',
    'sendmail_open_stat_conv'=>'Конверсия',
    'sendmail_open_stat_conv_send'=>'Всего отправлено',
    'sendmail_open_stat_conv_open'=>'Уникальных открытий',
    'sendmail_open_stat_conv_per'=>'Конверсия',
    'sendmail_open_stat_open'=>'Открытия письма',
    'sendmail_open_stat_open_date'=>'Дата',
    'sendmail_open_stat_open_email'=>'Адрес',
    'sendmail_open_stat_open_ip'=>'IP',
    'sendmail_open_stat_open_not_found'=>'Не определен',

    'sendmail_folder_array' => [
        1=>["title"=>"Входящие", "icon"=>"inbox", "color"=>"inverse"],
        2=>["title"=>"Отправленные", "icon"=>"send", "color"=>"success"],
        3=>["title"=>"Черновики", "icon"=>"pencil", "color"=>"primary"],
        4=>["title"=>"Корзина", "icon"=>"trash", "color"=>"danger"],
    ],
    'sendmail_label_array' => [
        1=>["title"=>"Системные", "color"=>"inverse"],
        2=>["title"=>"Важные", "color"=>"danger"],
    ],

	'sendmail_signature'=>'Ваши подписи',
    'sendmail_signature_add'=>'Добавить подпись',
    'sendmail_signature_edit'=>'Редактировать подпись',
    'sendmail_signature_title'=>'Название подписи',
    'sendmail_signature_desc'=>'Ваша подпись',
    'sendmail_signature_title_error'=>'Название подписи короче '.config::app('app_min_strlen').'-х символов',
    'sendmail_signature_desc_error'=>'Подпись короче '.config::app('app_min_strlen').'-х символов',
];
?>
