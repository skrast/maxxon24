<?php
namespace modules;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class import {
	static $mod_folder = "";
	static $main_title =  array();

	static $str_row = 6;

	function __construct() {
		self::$main_title[] =  \twig::$lang["import_name"];
	}

	static function module_info($custom='') {
		$module['module_name'] = \twig::$lang["import_name"];
		$module['module_name_short'] = \twig::$lang["import_name_short"];
		$module['module_tag'] = "import";
		$module['module_version'] = "1.0";
		$module['module_desc'] = \twig::$lang["import_description"];
		$module['module_author'] = "Максим Медведев";
		$module['module_copy'] = "&copy; 2018 Реймакс";
		$module['module_settings'] = 1;
		$module['module_allow_link'] = 1;

		self::$mod_folder = '/' . $module['module_tag'] . "/templates/";
		return ($custom!='') ? $module[$custom] : $module;
	}

	static function import_start() {

		// доступные языковые версии
		$lang_array = \config::get_lang();
		\twig::assign('lang_array', $lang_array);
		// доступные языковые версии


		// country
		if(!$_REQUEST['country_id'] && !$_REQUEST['city_id']) {
			$sql = \DB::fetchrow("
				SELECT * FROM
					" . \DB::$db_prefix . "_module_country
				ORDER BY country_abbr ASC
			");
			$country_list = [];
			foreach($sql as $row) {
				$row = self::country_info($row);

				$row->country_title_full = implode(", ", $row->country_title_full);
				$row->country_title_short = implode(", ", $row->country_title_short);
				$country_list[$row->country_md5] = $row;
			}
			\twig::assign('country_list', $country_list);
		}
		// country

		// city
		if($_REQUEST['country_id'] && !$_REQUEST['city_id']) {

			$country_info = \DB::row("
				SELECT * FROM
					" . \DB::$db_prefix . "_module_country
				WHERE country_id = '".(int)$_REQUEST['country_id']."'
			");
			$country_info = self::country_info($country_info);

			$country_info->country_title_full = implode(", ", $country_info->country_title_full);
			$country_info->country_title_short = implode(", ", $country_info->country_title_short);
			\twig::assign('country_info', $country_info);

			$sql = \DB::fetchrow("
				SELECT * 
				FROM " . \DB::$db_prefix . "_module_city as ct 
				JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country 
				WHERE country_id = '".(int)$_REQUEST['country_id']."'
				ORDER BY city_id ASC
			");
			
			$city_list = [];
			foreach($sql as $row) {
				$row = self::city_info($row);

				$row->city_title = implode(", ", $row->city_title);
				$city_list[$row->city_md5] = $row;
			}

			\twig::assign('city_list', $city_list);
		}
		// city

		// metro
		if($_REQUEST['city_id']) {
			$country_info = \DB::row("
				SELECT * FROM
					" . \DB::$db_prefix . "_module_country
				WHERE country_id = '".(int)$_REQUEST['country_id']."'
			");
			$country_info = self::country_info($country_info);

			$country_info->country_title_full = implode(", ", $country_info->country_title_full);
			$country_info->country_title_short = implode(", ", $country_info->country_title_short);
			\twig::assign('country_info', $country_info);

			$city_info = \DB::row("
				SELECT * FROM
					" . \DB::$db_prefix . "_module_city
				WHERE city_id = '".(int)$_REQUEST['city_id']."'
			");
			$city_info = self::city_info($city_info);
			$city_info->city_title = implode(", ", $city_info->city_title);
			\twig::assign('city_info', $city_info);

			$sql = \DB::fetchrow("
				SELECT * 
				FROM " . \DB::$db_prefix . "_module_metro as mt 
				JOIN " . \DB::$db_prefix . "_module_city as ct on ct.city_id = mt.metro_city 
				JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country
				WHERE country_id = '".(int)$_REQUEST['country_id']."' AND city_id = '".(int)$_REQUEST['city_id']."'
				ORDER BY metro_id ASC
			");
			$metro_list = [];
			foreach($sql as $row) {
				$row = self::metro_info($row);

				$row->metro_title = implode(", ", $row->metro_title);
				$metro_list[$row->metro_md5] = $row;
			}

			\twig::assign('metro_list', $metro_list);
		}
		// metro


		\twig::assign('content', \twig::fetch(self::$mod_folder.'import_start.tpl'));
	}


	static function delete_metro() {
		$metro_id = (int)$_REQUEST['city_id'];

		\DB::query("
			DELETE FROM
				" . \DB::$db_prefix . "_module_metro
			WHERE metro_id = '".$metro_id."'
		");

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id='.(int)$_REQUEST['country_id'].'&city_id='.(int)$_REQUEST['city_id']);
		exit;
	}
	static function delete_city() {
		$city_id = (int)$_REQUEST['city_id'];

		\DB::query("
			DELETE FROM
				" . \DB::$db_prefix . "_module_city
			WHERE city_id = '".(int)$city_id."'
		");

		\DB::query("
			DELETE FROM
				" . \DB::$db_prefix . "_module_metro
			WHERE metro_city = '".(int)$city_id."'
		");

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id='.(int)$_REQUEST['country_id']);
		exit;
	}
	static function delete_country() {
		$country_id = (int)$_REQUEST['country_id'];

		$sql = \DB::query("
			SELECT city_id 
			FROM " . \DB::$db_prefix . "_module_city
			WHERE city_country  = '".(int)$country_id."'
		");
		foreach($sql as $row) {
			\DB::query("
				DELETE FROM
					" . \DB::$db_prefix . "_module_metro
				WHERE metro_city = '".(int)$row->city_id."'
			");
		}

		\DB::fetchrow("
			DELETE FROM
				" . \DB::$db_prefix . "_module_city
			WHERE city_country  = '".(int)$country_id."'
		");

		\DB::fetchrow("
			DELETE FROM
				" . \DB::$db_prefix . "_module_country
			WHERE country_id = '".(int)$country_id."'
		");

		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=import&module_action=import_start');
		exit;
	}

	static function import_start_parser() {
		$module_info = self::module_info();
		$targetPath = BASE_DIR."/".\config::app('app_upload_dir')."/".\config::app('app_module_dir')."/" . $module_info['module_tag'] ."/";

		$file_data = $_FILES['file_name']['name'];
		$file_tmp = $_FILES['file_name']['tmp_name'];
		$file_data = rename_file($file_data, $targetPath);

		if ($file_data != false) {
			$targetFile =  $targetPath . $file_data;
			if(move_uploaded_file($file_tmp, $targetFile)) {

				$get_row = self::parse_xlsx($targetFile);

				// country
				$sql = \DB::fetcharray("
					SELECT * FROM
						" . \DB::$db_prefix . "_module_country
				");
				$country_list = [];
				foreach($sql as $row) {
					$country_list[$row['country_md5']] = $row;
				}
				// country

				// city
				$sql = \DB::fetcharray("
					SELECT * 
					FROM " . \DB::$db_prefix . "_module_city as ct 
					JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country 
				");
				foreach($sql as $row) {
					$country_list[$row['country_md5']]['city_list'][$row['city_md5']] = $row;
				}
				// city

				// metro
				$sql = \DB::fetcharray("
					SELECT * 
					FROM " . \DB::$db_prefix . "_module_metro as mt 
					JOIN " . \DB::$db_prefix . "_module_city as ct on ct.city_id = mt.metro_city 
					JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country
				");
				foreach($sql as $row) {
					$country_list[$row['country_md5']]['city_list'][$row['city_md5']]['metro_list'][$row['metro_md5']] = $row;
				}
				// metro

				$last_country = 0;
				$last_city = 0;
				$last_metro = 0;

				foreach($get_row as $row) {

					if($row[7] == "Россия") $row[7] = "Российская Федерация";

					$head = $row;

					$main_data = array_slice($head, 0, 5);
					$row_chank = array_splice($head, 5);
					$main_list = array_chunk($row_chank, 6);

					$lang_title = [];
					foreach ($main_list as $key => $value) {
						for ($i=0, $max = count($value); $i < $max; $i++) {
							if($value[0]) {
								$lang_title[$i][$value[0]] = $value[$i]; 
							}
						}
					}

					//foreach($main_list as $chank) {
						//if(count($chank) == self::$str_row) {

							if(!empty($lang_title[2]["ru"])) {
								// работа со странами

								$country_md5 = md5($row[2].$main_data[2].$row[7]);

								if(!$country_list[$country_md5]) {
									$q = "
										INSERT INTO
											" . \DB::$db_prefix . "_module_country
										SET
											country_md5 = '".$country_md5."',
											country_title_short = '".clear_text(serialize($lang_title[2]))."',
											country_title_full = '".clear_text(serialize($lang_title[1]))."',
											country_abbr = '".clear_text($main_data[2])."',
											country_abbr_full = '".clear_text($main_data[3])."',
											country_code = '".clear_text($main_data[4])."',
											country_lang = '".clear_text(serialize($lang_title[0]))."',
											country_phone = '".clear_text($main_data[1])."'
									";
									\DB::query($q);

									$row['country_id'] = \DB::lastInsertId();
									$country_list[$country_md5] = $row;
									$last_country = $row['country_id'];
								} else {
									$q = "
										UPDATE
											" . \DB::$db_prefix . "_module_country
										SET
											country_title_short = '".clear_text(serialize($lang_title[2]))."',
											country_title_full = '".clear_text(serialize($lang_title[1]))."',
											country_abbr = '".clear_text($main_data[2])."',
											country_abbr_full = '".clear_text($main_data[3])."',
											country_code = '".clear_text($main_data[4])."',
											country_lang = '".clear_text(serialize($lang_title[0]))."',
											country_phone = '".clear_text($main_data[1])."'

										WHERE country_id = '".$country_list[$country_md5]['country_id']."'
									";
									\DB::query($q);

									$last_country = $country_list[$country_md5]['country_id'];
								}
								//echo $q . "<br>";
								// работа со странами
								
								// работа с городами
								if(!empty($lang_title[4]["ru"])) {
									$city_md5 = md5($row[2].$main_data[2].$row[7].$row[9]);

									if(!$country_list[$country_md5]['city_list'][$city_md5]) {
										$q = "
											INSERT INTO
												" . \DB::$db_prefix . "_module_city
											SET
												city_md5 = '".$city_md5."',
												city_country = '".(int)$last_country."',
												city_utm = '".clear_text($main_data[0])."',

												city_region = '".clear_text(serialize($lang_title[3]))."',
												city_title = '".clear_text(serialize($lang_title[4]))."'
										";
										\DB::query($q);

										$row['city_id'] = \DB::lastInsertId();
										$country_list[$country_md5]['city_list'][$city_md5] = $row;
										$last_city = $row['city_id'];
									} else {
										$q = "
											UPDATE
												" . \DB::$db_prefix . "_module_city
											SET
												city_utm = '".clear_text($main_data[0])."',
												city_country = '".(int)$last_country."',
												city_region = '".clear_text(serialize($lang_title[3]))."',
												city_title = '".clear_text(serialize($lang_title[4]))."'

											WHERE city_id = '".$country_list[$country_md5]['city_list'][$city_md5]['city_id']."'
										";
										\DB::query($q);

										$last_city = $country_list[$country_md5]['city_list'][$city_md5]['city_id'];
									}
								}
								//echo $q . "<br>";
								// работа с городами

								// работа с метро
								if(!empty($lang_title[5]["ru"])) {
									$metro_md5 = md5($row[2].$main_data[2].$row[7].$row[9].$row[10]);

									if(!$country_list[$country_md5]['city_list'][$city_md5]['metro_list'][$metro_md5]) {
										$q = "
											INSERT INTO
												" . \DB::$db_prefix . "_module_metro
											SET
												metro_md5 = '".$metro_md5."',
												metro_city = '".(int)$last_city."',
												metro_title = '".clear_text(serialize($lang_title[5]))."'
										";
										\DB::query($q);

										$row['metro_id'] = \DB::lastInsertId();
										$country_list[$country_md5]['city_list'][$city_md5]['metro_list'][$metro_md5] = $row;
										$last_metro = $row['metro_id'];
									} else {
										$q = "
											UPDATE
												" . \DB::$db_prefix . "_module_metro
											SET
												metro_md5 = '".$metro_md5."',
												metro_city = '".(int)$last_city."',
												metro_title = '".clear_text(serialize($lang_title[5]))."'
											WHERE city_id = '".$country_list[$country_md5]['city_list'][$city_md5]['metro_list'][$metro_md5]['metro_id']."'
										";
										\DB::query($q);

										$last_metro = $country_list[$country_md5]['city_list'][$city_md5]['metro_list'][$metro_md5]['metro_id'];
									}
								}
								//echo $q . "<br>";
								// работа с метро
								
							}

						//}

						//echo "<br>";
					//}


				}	

				//dd($row_chank);
			}
		}

		/*

		TRUNCATE TABLE `writer_module_metro`;
		TRUNCATE TABLE `writer_module_city`;
		TRUNCATE TABLE `writer_module_country`;

*/


		header('Location:'.ABS_PATH_ADMIN_LINK.'?do=module&sub=mod_edit&module_tag=import&module_action=import_start');
		exit;
	}

	static function import_start_export() {

		// country
		/*$sql = \DB::fetchrow("
			SELECT * FROM
				" . \DB::$db_prefix . "_module_country
			ORDER BY country_abbr ASC
		");
		$country_list = [];
		foreach($sql as $row) {
			$row->country_title_full = unserialize($row->country_title_full);
			$row->country_title_short = unserialize($row->country_title_short);
			$row->country_lang = unserialize($row->country_lang);

			$row->country_title_full = array_filter($row->country_title_full);
			$row->country_title_short = array_filter($row->country_title_short);
			$row->country_lang = array_filter($row->country_lang);

			$country_list[$row->country_md5] = $row;
		}*/
		// country

		// city
		$sql = \DB::fetchrow("
			SELECT * 
			FROM " . \DB::$db_prefix . "_module_city as ct 
			JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country 
			ORDER BY country_abbr ASC
		");
		foreach($sql as $row) {
			$row = self::country_info($row);
			$row = self::city_info($row);
			
			$country_list[$row->city_md5] = $row;
		}
		// city

		// metro
		$sql = \DB::fetchrow("
			SELECT * 
			FROM " . \DB::$db_prefix . "_module_metro as mt 
			JOIN " . \DB::$db_prefix . "_module_city as ct on ct.city_id = mt.metro_city 
			JOIN " . \DB::$db_prefix . "_module_country as cy on cy.country_id = ct.city_country
		");
		foreach($sql as $row) {
			$row = self::metro_info($row);

			$country_list[$row->city_md5]->metro_list[$row->metro_md5] = $row;
		}
		// metro

		$targetFile = BASE_DIR . '/cache/import/export.xlsx';
		if(!is_file($targetFile)) {
			touch($targetFile);
		}

		$export = [];
		array_push($export[0], []);
		$k = 1;
		foreach ($country_list as $row) {

			
			if($row->metro_list) {
				foreach ($row->metro_list as $value) {

					$export[$k] = [
						0 => $row->city_utm,
						1 => $row->country_phone,
						2 => $row->country_abbr,
						3 => $row->country_abbr_full,
						4 => $row->country_code,
					];

					foreach($row->country_lang as $lang) {

						array_push($export[$k], ($lang ? $lang : ""));

						$country_title_full = $row->country_title_full[$lang] ? $row->country_title_full[$lang] : "";
						array_push($export[$k], $country_title_full);

						$country_title_short = $row->country_title_short[$lang] ? $row->country_title_short[$lang] : "";
						array_push($export[$k], $country_title_short);

						$city_region = $row->city_region[$lang] ? $row->city_region[$lang] : "";
						array_push($export[$k], $city_region);

						$city_title = $row->city_title[$lang] ? $row->city_title[$lang] : "";
						array_push($export[$k], $city_title);
						
						$metro_title = $value->metro_title[$lang] ? $value->metro_title[$lang] : "";
						array_push($export[$k], $metro_title);

						ksort($export[$k]);
						
					}

					$k++;
				}
			} else {

				$export[$k] = [
					0 => $row->city_utm,
					1 => $row->country_phone,
					2 => $row->country_abbr,
					3 => $row->country_abbr_full,
					4 => $row->country_code,
				];

				foreach($row->country_lang as $lang) {
					array_push($export[$k], ($lang ? $lang : ""));

					$country_title_full = $row->country_title_full[$lang] ? $row->country_title_full[$lang] : "";
					array_push($export[$k], $country_title_full);

					$country_title_short = $row->country_title_short[$lang] ? $row->country_title_short[$lang] : "";
					array_push($export[$k], $country_title_short);

					$city_region = $row->city_region[$lang] ? $row->city_region[$lang] : "";
					array_push($export[$k], $city_region);

					$city_title = $row->city_title[$lang] ? $row->city_title[$lang] : "";
					array_push($export[$k], $city_title);
					
					array_push($export[$k], "");

					ksort($export[$k]);
				}
				$k++;
			}
		}
			

		

		
		require_once(BASE_DIR . '/vendor/PHPExcel/PHPExcel.php');
		// create php excel object
		$PHPExcel_file = new \PHPExcel();

		// set active sheet 
		$PHPExcel_file->setActiveSheetIndex(0);

		$PHPExcel_file->getActiveSheet()->getDefaultColumnDimension()->setWidth("15");
		$PHPExcel_file->getActiveSheet()->fromArray($export);


		//save our workbook as this file name
		//mime type
		//OLD EXCEL header('Content-Type: application/vnd.ms-excel'); 
		//NEW EXCEL
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//tell browser what's the file name
		header('Content-Disposition: attachment;filename="export.xlsx"');
		header('Cache-Control: max-age=0'); //no cache
		// clean data
		ob_end_clean();
		//OLD EXCEL $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
		//NEW EXCEL 
		$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel_file, 'Excel2007');

		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');


		//dd($export);

		exit;
	}


	static function country_info($country_info) {
		$country_info->country_title_full = unserialize($country_info->country_title_full);
		$country_info->country_title_short = unserialize($country_info->country_title_short);
		$country_info->country_lang = unserialize($country_info->country_lang);

		$country_info->country_title_full = array_filter($country_info->country_title_full);
		$country_info->country_title_short = array_filter($country_info->country_title_short);
		$country_info->country_lang = array_filter($country_info->country_lang);

		return $country_info;
	}

	static function city_info($city_info) {
		$city_info->city_title = unserialize($city_info->city_title);
		$city_info->city_region = unserialize($city_info->city_region);

		$city_info->city_title = array_filter($city_info->city_title);
		$city_info->city_region = array_filter($city_info->city_region);

		return $city_info;
	}

	static function metro_info($metro_info) {
		$metro_info->metro_title = unserialize($metro_info->metro_title);

		$metro_info->metro_title = array_filter($metro_info->metro_title);

		return $metro_info;
	}


	static function parse_xlsx($targetFile) {

		require_once(BASE_DIR . '/vendor/PHPExcel/PHPExcel.php');
		$PHPExcel_file = \PHPExcel_IOFactory::load($targetFile);
		$PHPExcel_file->setActiveSheetIndex(0);
		$data_import = excel2mysql($PHPExcel_file->getActiveSheet());
			
		unset($data_import[0]);
		@unlink($targetFile);

		return $data_import;
	}

}
?>
