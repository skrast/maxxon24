<?php
namespace MyRestApi;

class Api {
	static $data;
	static $user_id = "";
	static $user_permission = "";

	static function bild_var() {
		parse_str(file_get_contents("php://input"), $post_vars);
        foreach ($post_vars as $key => $value) {
        	$key = str_replace('amp;', '', $key);
        	$_REQUEST[$key]=$value;
        }
	}


	public function auth() {
		self::bild_var();

		if(\backend::security()===false) {
			return '100';
		}

		// включено ли апи
		if(!\config::app('app_api')) {
			return '101';
		}
		// включено ли апи

		// проверка кода доступа
        if($_REQUEST['api_key']!=\config::app('app_key_access')) {
        	return '102';
        }
        // проверка кода доступа

        return true;
	}


	public function auth_user() {

        // проверка кода доступа пользователя
        if($_REQUEST['user_hash']) {
        	$user_info = \DB::row("
				SELECT usr.*, gr.user_group_permission
				FROM " . \DB::$db_prefix . "_users as usr
				JOIN " . \DB::$db_prefix . "_users_groups as gr on gr.user_group=usr.user_group
				WHERE user_secret = '".addslashes($_REQUEST['user_hash'])."'
				LIMIT 1
			");

			if(!$user_info) {
				return '103';
			} else {
				$user_info->user_permission = explode("|", $user_info->user_group_permission);
				if(!in_array('alles', $user_info->user_permission) && !in_array('api_access', $user_info->user_permission)) {
					return '104';
				}
			}

			self::$user_id = $user_info->id;
			self::$user_permission = $user_info->user_permission;
        }
        // проверка кода доступа пользователя
        return true;
	}

	public function module($tag) {
		// базовый доступ
    	$check = self::auth();
    	if($check!==true) {
    		throw new \ApiError($check);
    	}

		$name_class = "modules\\".$tag."Api";
		$execute = $_REQUEST['execute'];

		if(
			isset($tag)
			&& array_key_exists($tag, \module::$modules)
			&& class_exists($name_class)
			&& method_exists($name_class, $execute)
			&& in_array('hook_api', \module::$modules[$tag]['module_hook'])
		) {
			return $name_class::$execute();
		} else {
			throw new \ApiError(502);
		}
	}
}
?>
