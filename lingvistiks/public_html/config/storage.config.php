<?php
	return [
		'app_type_session' => 'db', // file, db, redis
		'app_type_cache' => 'file', // file, redis
		'app_redis' => [
			'session' => [
				'host' => '127.0.0.1',
				'port' => 6379,
				'database' => 15,
			],
			'cache' => [
				'host' => '127.0.0.1',
				'port' => 6379,
				'database' => 15,
			],
		],
	];
?>
