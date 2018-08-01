<?php
$sql = [
	"install" => [
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_page (
				`page_id` int(15) NOT NULL AUTO_INCREMENT,
			    `page_title` varchar(255) NOT NULL,
			    `page_alias` varchar(255) NOT NULL,
			    `page_meta_description` varchar(255) NOT NULL,
			    `page_meta_keywords` varchar(255) NOT NULL,
			    `page_meta_robots` enum('index,follow','index,nofollow','noindex,nofollow') NOT NULL DEFAULT 'index,follow',
			    `page_lang` varchar(2) NOT NULL DEFAULT 'ru',
			    `page_index` enum('0','1') NOT NULL DEFAULT '0',
			    `page_text` longtext NOT NULL,
			    `page_preview` varchar(255) NOT NULL,
			    `page_add` int(15) NOT NULL,
			    `page_owner` int(15) NOT NULL,
			    `page_edit` int(15) NOT NULL,
			    `page_edit_author` int(15) NOT NULL,
			    `page_status` int(2) NOT NULL,
			    `page_folder` int(15) NOT NULL,
			    `page_access` int(1) NOT NULL DEFAULT '1',
			    `page_view` int(15) NOT NULL,
				`page_youtube` varchar(255) NOT NULL,
				`page_landing` enum('0','1') NOT NULL DEFAULT '0',
				`page_landing_in_site` enum('0','1') NOT NULL DEFAULT '0',
				`page_landing_sourse` longtext NOT NULL,
			    PRIMARY KEY (`page_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_page_folder (
				`folder_id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
			    `folder_title` varchar(255) NOT NULL,
			    `folder_add` int(15) NOT NULL,
			    `folder_add_author` int(15) NOT NULL,
			    `folder_edit` int(15) NOT NULL,
			    `folder_edit_author` int(15) NOT NULL,
			    PRIMARY KEY (`folder_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_page_navi (
				`navi_id` int(15) NOT NULL AUTO_INCREMENT,
			    `navi_title` varchar(255) NOT NULL,
			    `navi_lang` varchar(2) NOT NULL DEFAULT 'ru',
			    UNIQUE KEY `navi_id` (`navi_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_page_navi_item (
				`item_id` int(15) NOT NULL AUTO_INCREMENT,
			    `navi_id` int(15) NOT NULL,
			    `parent_id` int(15) NOT NULL,
			    `item_title` varchar(255) NOT NULL,
			    `item_page` int(15) NOT NULL,
			    `item_sort` int(15) NOT NULL,
			    UNIQUE KEY `item_id` (`item_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_page_files (
				`file_id` int(15) NOT NULL AUTO_INCREMENT,
				`file_name` varchar(255) NOT NULL,
				`file_path` varchar(255) NOT NULL,
				`file_page` int(15) NOT NULL,
				`file_date` int(15) NOT NULL,
				UNIQUE KEY `file_id` (`file_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
	],
	"uninstall" => [
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_page",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_page_folder",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_page_navi",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_page_navi_item",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_page_files",
	],
];
?>
