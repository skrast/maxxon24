<?php
class sessionDB extends session
{

	/* Create a connection to a database */
	function __construct()
	{

	}

	static function work() {
		session_set_save_handler (
			array(parent::$strategy, '_open'),
			array(parent::$strategy, '_close'),
			array(parent::$strategy, '_read'),
			array(parent::$strategy, '_write'),
			array(parent::$strategy, '_destroy'),
			array(parent::$strategy, '_gc')
		);
	}

	/* Open session */
	function _open($sess_save_path, $session_name)
	{
		return true;
	}

	/* Close session */
	function _close() {
		DB::query("DELETE FROM " . DB::$db_prefix . "_sessions WHERE expiry < '".time()."'");
		return true;
	}

	/* Read session */
	function _read($id)
	{
		$row = DB::row("SELECT value, Ip FROM " . DB::$db_prefix . "_sessions WHERE sesskey = '" . $id . "' AND expiry > '" . time() . "' ");
		if ($row->Ip == $_SERVER['REMOTE_ADDR']) {
			return $row->value;
		}
		return "";
	}

	/* Write new data */
	function _write($id, $sess_data)
	{
		//$row = DB::row("SELECT value FROM " . DB::$db_prefix . "_sessions WHERE sesskey = '".$id."'");
		$time = time()+parent::$sess_lifetime;

		DB::query("
			REPLACE INTO " . DB::$db_prefix . "_sessions
			VALUES (
				'".$id."',
				'".$time."',
				'".addslashes($sess_data)."',
				'".$_SERVER['REMOTE_ADDR']."'
			)
		");

		return true;
	}

	/* Destroy session */
	function _destroy($id)
	{
		DB::query("DELETE FROM " . DB::$db_prefix . "_sessions WHERE sesskey = '".$id."'");
		return true;
	}

	/* Garbage collection, deletes old sessions */
	function _gc($maxlifetime)
	{
		DB::query("DELETE FROM " . DB::$db_prefix . "_sessions WHERE expiry < UNIX_TIMESTAMP(NOW() - '" . $maxlifetime . "')");
		return true;
	}

	function __destruct ()
	{
		register_shutdown_function('session_write_close');
	}

	static function delete_session() {
		DB::query("DELETE FROM " . DB::$db_prefix . "_sessions WHERE expiry < '" . time() . "' OR value = '' " );
	}
}
?>
