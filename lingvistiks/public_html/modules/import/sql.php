<?php
$sql = [
	"install" => [
		"
		CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_city (
			`city_id` int(15) NOT NULL AUTO_INCREMENT,
			`city_md5` varchar(255) NOT NULL,
			`city_title` text NOT NULL,
			`city_region` text NOT NULL,
			`city_country` int(15) NOT NULL,
			`city_utm` varchar(5) NOT NULL,
			UNIQUE KEY `city_id` (`city_id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
		",
		"
		CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_country (
			`country_id` int(15) NOT NULL AUTO_INCREMENT,
			`country_md5` varchar(255) NOT NULL,
			`country_title_short` text NOT NULL,
			`country_title_full` text NOT NULL,
			`country_lang` text NOT NULL,
			`country_abbr` varchar(2) NOT NULL,
			`country_abbr_full` varchar(5) NOT NULL,
			`country_code` int(5) NOT NULL,
			`country_phone` varchar(5) NOT NULL,
			UNIQUE KEY `country_id` (`country_id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
		",
		"
		CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_metro (
			`metro_id` int(15) NOT NULL AUTO_INCREMENT,
			`metro_md5` varchar(255) NOT NULL,
			`metro_title` text NOT NULL,
			`metro_city` int(15) NOT NULL,
			UNIQUE KEY `metro_id` (`metro_id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
		",
	],
	"uninstall" => [
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_city",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_country",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_metro",
	],
];
?>
