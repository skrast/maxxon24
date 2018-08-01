<?php
$sql = [
	"install" => [
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_tiket (
				`tiket_id` int(15) NOT NULL AUTO_INCREMENT,
			    `tiket_title` varchar(255) NOT NULL,
			    `tiket_user_email` varchar(255) NOT NULL,
			    `tiket_user_name` varchar(255) NOT NULL,
			    `tiket_user_phone` varchar(255) NOT NULL,
			    `tiket_type` varchar(255) NOT NULL,
			    `tiket_text` text NOT NULL,
			    `tiket_group` int(15) NOT NULL,
			    `tiket_add` int(15) NOT NULL,
			    `tiket_edit` int(15) NOT NULL,
			    `tiket_owner` int(15) NOT NULL,
			    `tiket_answer` int(15) NOT NULL,
				`tiket_close` int(1) NOT NULL DEFAULT '0',
				`tiket_tags` varchar(255) NOT NULL,
				`tiket_owner_open` enum('0','1') NOT NULL DEFAULT '0',
				`tiket_answer_open` enum('0','1') NOT NULL DEFAULT '0',
			    PRIMARY KEY (`tiket_id`),
			    KEY `tiket_group` (`tiket_group`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE IF NOT EXISTS ".DB::$db_prefix."_module_tiket_group (
				`tiket_group_id` int(15) NOT NULL AUTO_INCREMENT,
			    `tiket_group_title` varchar(255) NOT NULL,
			    `tiket_group_add` int(15) NOT NULL,
			    `tiket_group_edit` int(15) NOT NULL,
			    `tiket_group_owner` int(15) NOT NULL,
			    PRIMARY KEY (`tiket_group_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
		",
		"
			CREATE TABLE ".DB::$db_prefix."_module_tiket_comment (
				`comment_id` int(15) NOT NULL AUTO_INCREMENT,
				`tiket_id` int(15) NOT NULL,
				`comment_owner` int(15) NOT NULL,
				`comment_date` int(15) NOT NULL,
				`comment_text` text NOT NULL,
				PRIMARY KEY (`comment_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		",
	],
	"uninstall" => [
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_tiket",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_tiket_group",
		"DROP TABLE IF EXISTS ".DB::$db_prefix."_module_tiket_comment",
	],
];
?>
