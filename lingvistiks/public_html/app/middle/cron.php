<?php
class cron {
	static function bild_cron() {
		set_time_limit(20000);
		ini_set("memory_limit", "512M");

		$skip = array('.', '..');
		$cron_folder = BASE_DIR."/app/cron/";
		$files = scandir($cron_folder);

		// loader
		require_once BASE_DIR.'/vendor/loader/vendor/autoload.php';
		$loader = new Nette\Loaders\RobotLoader;

        $to_class = [];
		foreach($files as $file) {
			if(!in_array($file, $skip)) {
				$to_class[] = $file;
				$loader->addDirectory($cron_folder.$file);
			}
		}
		$loader->setTempDirectory(BASE_DIR . '/cache/classmap/');
		$loader->excludeDirectory(BASE_DIR . '/app/fields');
		$loader->excludeDirectory(BASE_DIR . '/app/helper');
		$loader->excludeDirectory(BASE_DIR . '/app/sql');
		$loader->register(); // Run the RobotLoader
		// loader

		module::get_active_modules();

		switch ($_REQUEST['single']) {
			case 'task':
				$class_name = $_REQUEST['open'];
				if(class_exists($class_name)) {
					self::cron_task($class_name);
				}
			break;

			case 'module':
				$class_name = $_REQUEST['open'];
				self::cron_module($class_name);
			break;

			default:
				$module = module::$modules;
				foreach ($module as $tag => $value) {
					self::cron_module($tag);
				}

				foreach ($to_class as $class_name) {
					if(property_exists($class_name, 'group')) {
						self::cron_task($class_name);
					}
				}
			break;
		}

		echo 'success';
	}

	static function cron_module($tag) {
		$module = module::$modules;
		$class_name = "modules\\".$tag."Cron";
		if(
			$module[$tag]['module_cron'] == 1
			&& class_exists($class_name)
			&& method_exists($class_name, 'cron')
		) {

			$class_name::cron();
		}
	}

	static function cron_task($class_name) {
		$task_file = BASE_DIR . "/" . config::app("app_cron_dir") . "/" . $class_name. ".txt";
		create_file($task_file, time());

		$last_time = file_get_contents($task_file);
		$last_time = (int)$last_time;

		if((time()-$last_time >= $class_name::$period || $last_time == 0) && method_exists($class_name, "run_task")) {
			$class_name::run_task();
			file_put_contents($task_file, $last_time+$class_name::$period);
		}
	}
}
?>
