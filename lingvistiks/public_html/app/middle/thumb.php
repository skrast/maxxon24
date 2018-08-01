<?php

class thumb {
	static function bild_thumb($width_custom='',$height_custom='', $override = 0) {

		if (!defined('BASE_DIR')) exit;

		// Если высота не в диапазоне от 10 до 600 (ну его нафиг такие миниатюры) устанавливаем = 0
		$height = (isset($_REQUEST['height']) && is_numeric($_REQUEST['height']) &&
				$_REQUEST['height'] > 10 && $_REQUEST['height'] <= 600) ? (int)$_REQUEST['height'] : 0;

		// Если ширина не в диапазоне от 10 до 800 (ну его нафиг такие миниатюры) устанавливаем = 0 или 120 если высота = 0
		$width = (isset($_REQUEST['width']) && is_numeric($_REQUEST['width']) &&
				$_REQUEST['width'] > 10 && $_REQUEST['width'] <= 600) ? (int)$_REQUEST['width'] : (0 == $height ? 120 : 0);

		$width = $width_custom ? $width_custom : $width;
		$height = $height_custom ? $height_custom : $height;

		// Удаляем непечатаемые символы и ведущий слэш
		if (! empty($_REQUEST['thumb'])) $filename = ltrim(preg_replace('/[^\x20-\xFF]/', '', $_REQUEST['thumb']), '/');

		// Формируем полный путь к оригиналу изображения
		$file = BASE_DIR . '/' . ltrim($filename, '/');
		$thumb_dir = BASE_DIR . '/'.config::app('app_upload_dir').'/'.config::app('app_thumbnail_dir').'/';

		if (file_exists($file)) {
			$filename = basename($file);
			$file_dir = dirname($file);
			$dir = $thumb_dir . ($width ? $width : '').'x'.($height ? $height : '');

			// Формируем путь к миниатюре с учетом того что миниатюры должны храниться в папке thumbnail,
			// а имя файла миниатюры содержит размеры миниатюры (для хранения миниатюр с разными размерами)
			$thumb_file = $dir . '/' . $filename;

			// Проверяем наличие миниатюры с нужными размерами
			if (file_exists($thumb_file)&&filemtime($thumb_file)>filemtime($file) && $override != 1)
			{
				$img_data = @getimagesize($file);
				header('Content-Type:' . $img_data['mime'], true);
				header("Last-Modified: ".gmdate("D, d M Y H:i:s".filemtime($thumb_file))." GMT");
				header("Content-Length: " . (string) filesize($thumb_file), true);
				readfile($thumb_file);
				exit;
			}

			// Проверяем наличие папки для миниатюр и если её нет - создаём
			if (! file_exists($dir))
			{
				$oldumask = umask(0);
				@mkdir($dir, 0777);
				umask($oldumask);
			}

			$options = array('jpegQuality' => config::app('AVATAR_QQ'));
			require_once BASE_DIR.'/vendor/PHPThumb/vendor/autoload.php';
			$thumb = new PHPThumb\GD($file, $options);

			$thumb->resize($width, $height);
			$thumb->save($thumb_file);
			$thumb->show();
		}
	}
}
?>
