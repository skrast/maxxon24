<?php
	return [
		'app_ver' => '3.20',
		'app_charset' => 'UTF-8',
		'app_csv_charset' => 'UTF-8',
		'app_use_https' => false,
		'app_session' => 3600,
		'app_agile_time' => 2592000, // 1 month
		'app_float' => 2,
		'app_min_strlen' => 2,
		'app_min_strlen_password'=>6,
		'app_name' => 'writeCRM',
		'app_account_name' => 'reimax',
		'app_lang' => 'ru',
	    'app_langs' => [
	        'ru' => 'РУС',
            'en' => 'ENG',
	        'es' => 'ESP',
	    ],
		'app_bandwidth_max' => 1048576*100*5, //100мб
		'app_db_max' => 1048576*100*5, //100мб
		'app_key_access' => '3d23wa3n49v0mc96f7d6',
		'app_write_log' => true,
		'app_profiling' => false,
		'app_twig_compile_check' => true,
		'app_csrf_validator' => true,

		'app_login_max_error' => 3,
		'app_login_ban_time' => 3600,

		'app_api' => true,
		'app_log_delete' => true,
		'app_error_delete' => true,

		'app_cron_dir' => 'cache/cron',
		'app_attach_dir' => 'cache/attachments',
		'app_session_dir' => 'cache/session',
		'app_storage_dir' => 'cache/storage',
		'app_twig_dir' => 'cache/twig',
		'app_update_dir' => 'cache/update',
		'app_classmap_dir' => 'cache/classmap',
		'app_import_dir' => 'cache/import',

		'app_upload_dir' => 'uploads',
		'app_thumbnail_dir' => 'thumbnail',
		'app_users_dir' => 'users',
		'app_users_orig_dir' => 'users_orig',
		'app_resume_dir' => 'resume',
		'app_resume_orig_dir' => 'resume_orig',
		'app_module_dir' => 'modules',
		'app_site_dir' => 'site',
		'app_message_dir' => 'message',
		'app_company_dir' => 'company',
		'app_company_orig_dir' => 'company_orig',
		'app_album_dir' => 'album',
		'app_album_resume_dir' => 'album_resume',

		'app_date_format' => 'd F Y',
		'app_date_format_time' => 'd.m.Y H:i',
		'app_date_short_format' => 'd.m.Y',
		'app_date_format_js' => 'dd.mm.yyyy hh:ss',
		'app_date_short_format_js' => 'dd.mm.yyyy',
		'app_date_xml_format_js' => '%d.%m.%Y %H:%i',
		'app_date_xml_short_format_js' => '%d.%m.%Y',
		'app_phone_format' => '+# (###) ###-##-##',
		'app_phone_format_free' => '###############',

		'app_checklist_level' => 5,

	    'app_time_long' => [
			'день дня дней' => 86400,
			'час часа часов' => 3600,
			'минута минуты минут' => 60,
			'секунда секунды секунд' => 1,
		],

		'app_email_reg' => 'reg@maxxon24.ru',
		'app_email_info' => 'info@maxxon24.ru',
		'app_email_support' => 'support@maxxon24.ru',
	    'app_city_order' => [
	        'city_id' => [
	            151 => -2,
	            682 => -1,
	        ]	        
	    ]
	];
?>
