<?php
$lang = [
	'table_title' => 'Название настроек',
	'table_work' => 'Настройки таблицы',
	'table_work_add' => 'Новый список',
	'table_list' => 'Сохраненные настройки',
	'table_default' => 'Настройки по умолчанию',
	'table_delete' => 'Удалить настройки',
	'table_enable' => 'Применить',

	'table_error_title' => 'Название короче '.config::app('app_min_strlen').'-х символов',
	'table_error_column' => 'Не выбраны колонки для отображения',
	'table_error_owner' => 'Вы не можете отредактировать эти настройки',

	'table_column' => [
		3 => [
			'deal_id' => 'Номер',
			'deal_make_wish' => 'В избранном',
			'deal_make_pin' => 'Закреплено',
			'deal_title' => 'Название',
			'company_title' => 'Клиенты',
			'deal_project' => 'Проект',
			'deal_budget' => 'Бюджет',
			'transactions_pay' => 'Оплачено',
			'transactions_dolg' => 'Задолженность',
			'deal_project_currency' => 'Валюта',
			'deal_owner' => 'Ответственный',
		    'deal_end' => 'Срок закрытия',
		    'deal_status' => 'Этап продаж',
			'deal_invite' => 'Источник обращения',
			'deal_deny' => 'Причина отказа',
			'deal_add' => 'Регистрация',
		    'deal_edit' => 'Последнее изменение',
			'deal_desc' => 'Примечание',
		],

		4 => [
			'company_id' => 'Номер',
		    'company_title' => 'Название',
			'company_project' => 'Проекты',
			'company_phone' => 'Телефоны',
		    'company_email' => 'Email',
			'company_count_lead' => 'Лиды',
			'company_count_deal' => 'Сделки',
		    'company_group' => 'Группа',
		    'company_status' => 'Статус',
		    'company_owner' => 'Ответственный',
		    'company_add' => 'Регистрация',
		    'company_edit' => 'Последнее изменение',
		    'company_desc' => 'Примечание',
		],

		5 => [
			'task_id' => 'Номер',
		    'task_title' => 'Название',
			'task_check_list' => 'Чеклист',
			'task_users' => 'Участники',
		    'task_start' => 'Начало выполнения',
			'task_end' => 'Срок выполнения',
		    'task_status' => 'Статус',
		    'task_type' => 'Тип',
		    'task_add' => 'Регистрация',
		    'task_edit' => 'Последнее изменение',
		    'task_warning' => 'Высокий приоритет',
		    'task_desc' => 'Примечание',
		],
	]
];
?>
