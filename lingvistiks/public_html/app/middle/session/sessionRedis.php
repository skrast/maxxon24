<?php
class sessionRedis extends session
{
	static $sess_lifetime;

	function __construct()
	{

	}

	static function work() {
		require_once(BASE_DIR . '/vendor/predis/vendor/autoload.php');

		$client = new Predis\Client(config::app('app_redis')['session'], array('prefix' => config::app('app_key_access').'_sessions:'));
		$handler = new Predis\Session\Handler($client, array('gc_maxlifetime' => parent::$sess_lifetime));
		$handler->register();
	}

	static function delete_session() {

	}
}
?>
