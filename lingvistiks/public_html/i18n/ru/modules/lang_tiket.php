<?php
$lang = [
	'tiket_name' => 'Тикеты',
    'tiket_name_short' => 'Тикеты',
    'tiket_description' => 'Предназначен для работы службы поддержки проекта',

    'tiket_add' => 'Добавить вопрос',
    'tiket_open' => 'Посмотреть вопрос',
    'tiket_edit' => 'Редактировать вопрос',
    'tiket_delete' => 'Удалить вопрос',
    'tiket_close' => 'Закрыть вопрос',
    'tiket_group_work' => 'Управление категориями',
	'tiket_group_edit' => 'Редактировать категорию',

    'tiket_id' => 'ID вопроса',
    'tiket_title' => 'Название',
    'tiket_tags' => 'Теги',
    'tiket_status' => 'Статус',
    'tiket_status_array' => [
		0 => 'Открыт',
	    1 => 'Закрыт',
	],

    'tiket_text' => 'Содержание',
    'tiket_group' => 'Категория',
    'tiket_reg' => 'Добавлен',
    'tiket_owner' => 'Автор',
    'tiket_answer' => 'Ответственный',
    'tiket_comment_count' => 'Комментариев',

    'tiket_comment' => 'Комментарии',
    'tiket_comment_text' => 'Сообщение',

    'tiket_group_title' => 'Название категории',
    'tiket_group_title_desc' => 'Добавить списком, каждый пункт с новой строки',

    'tiket_log_add' => 'Добавил вопрос',
    'tiket_comment_log_add' => 'Добавил ответ на вопрос',
    'tiket_log_delete' => 'Удалил вопрос',
    'tiket_log_close' => 'Закрыл вопрос',
    'tiket_group_log_add' => 'Добавил категорию',
    'tiket_group_log_edit' => 'Отредактировал категорию',
    'tiket_group_log_delete' => 'Удалил категорию',

    'tiket_title_error' => 'Пожалуйста, заполните поле обращения. Спасибо!',
    'tiket_text_error' => 'Пожалуйста, заполните поле обращения. Спасибо!',
    'tiket_comment_text_error' => 'Комментарий короче '.config::app('app_min_strlen').'-х символов',
    'tiket_group_error' => 'Категория вопроса указана неверно',
    'tiket_user_email_error' => 'Введён некорректный адрес электронной почты',
    'tiket_user_name_error' => 'Имя не введено',
    'tiket_type_error' => 'Не указано средство связи',
    'tiket_support_secure_error' => 'Пожалуйста, подтвердите ваше согласие с Правилами использования сервиса MAXXON и на обработку ваших персональных данных',

    'tiket_add_success' => 'Ваш вопрос добавлен. В ближайшее время с вами свяжется наш специалист. Обращению присвоем номер #LINK#',

    'tiket_mail_title' => 'На тикет был дан ответ',
    'tiket_mail_text' => 'В тикете №#ID# был добавлен комментарий. Открыть: #LINK#',
	'tiket_log_none' => 'не задан',
    
    'tiket_add_subject' => 'Сообщение с сайта',
    
];
?>