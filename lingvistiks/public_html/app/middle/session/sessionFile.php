<?php
class sessionFile extends session
{

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
	function _open($sess_save_path, $session_name){
		global $sess_save_path, $sess_session_name;

		$sess_save_path = BASE_DIR . '/' . config::app('app_session_dir');
		$sess_session_name = $session_name;

		return true;
	}

	/* Close session */
	function _close()
	{
		$this->_gc(parent::$sess_lifetime);
		return true;
	}

	/* Read session */
	function _read($id)
	{
		global $sess_save_path, $sess_session_name, $sess_session_id;

		$sess_session_id = $id;
		$sess_file = $this->_folder() . '/' . $id . '.sess';

		if (!file_exists($sess_file)) return "";

		if ($fp = @fopen($sess_file, "r"))
		{
			$sess_data = fread($fp, filesize($sess_file));
			return($sess_data);
		}
		else
		{
			return "";
		}
	}

	/* Write new data */
	function _write ($id, $sess_data)
	{
		global $sess_save_path, $sess_session_name, $sess_session_id;

		$sess_session_id = $id;
		$sess_file = $this->_folder() . '/' . $id . '.sess';

		if(!file_exists($this->_folder()))
			mkdir($this->_folder(), 0777, true);

		if ($fp = @fopen($sess_file, "w"))
		{
			return fwrite($fp, $sess_data);
		}
		else
		{
			return false;
		}
	}

	/* Destroy session */
	function _destroy ($id)
	{
		global $sess_save_path, $sess_session_name, $sess_session_id;

		$sess_session_id = $id;
		$sess_dir = $this->_folder();
		$sess_file = $sess_dir . '/' . $id . '.sess';

		return @unlink($sess_file);
	}

	/* Garbage collection, deletes old sessions */
	function _gc ($maxlifetime)
	{
		global $sess_save_path, $sess_session_id;

		$this->_clear($sess_save_path, 'sess', $maxlifetime);

		return true;
	}

	function _clear($dir, $mask, $maxlifetime)
	{
		foreach(glob($dir . '/*') as $filename) {

			if(strtolower(substr($filename, strlen($filename) - strlen($mask), strlen($mask))) == strtolower($mask)) {
				if((filemtime($filename) + $maxlifetime) < time())
					@unlink($filename);
			}

			if(is_dir($filename))
				if (!count(glob($filename.'/*'))) @rmdir($filename);
				self::_clear($filename, $mask, $maxlifetime);
		}
	}

	function _folder()
	{
		global $sess_session_id, $sess_save_path;
		chmod($sess_save_path . '/' . mb_substr($sess_session_id, 0, 3), 0777);
		return $sess_save_path . '/' . mb_substr($sess_session_id, 0, 3);
	}

	function __destruct ()
	{
		register_shutdown_function('session_write_close');
	}

	static function delete_session() {
		rrmdir(BASE_DIR . "/" . config::app('app_session_dir'));
		mkdir(BASE_DIR . "/" . config::app('app_session_dir'),0777,true);
		chmod(BASE_DIR . "/" . config::app('app_session_dir'), 0777);
	}

}
?>
