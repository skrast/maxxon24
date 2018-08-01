<?php
class hook {

	function __construct() {

	}

	static function show_hook($type_hook, $param=null) {
		$show_module_name = [];
		$show_module = [];

		foreach (module::$modules as $key=>$value) {
			$name_class = "modules\\".$value['module_tag']."Hook";
			if(
				in_array($type_hook, $value['module_hook'])
				&& class_exists($name_class)
				&& method_exists($name_class, $type_hook)
				&& (in_array($value['module_tag'], $_SESSION['user_group_module']) || $_SESSION['alles'])
			) {
				$module_info = $name_class::module_info();
				$show_module[$key] = ['module_info'=>$module_info, "bild" => $name_class::$type_hook($param)];
			}
		}

		return $show_module;
	}
}
?>
