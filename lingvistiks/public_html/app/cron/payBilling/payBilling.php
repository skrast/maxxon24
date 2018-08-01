<?php
class payBilling {
	static $desc = "Списание средств пользователей";
	static $period = 1;
	static $group = 1;

	static function run_task() {

		set_time_limit(9999999);

		$users = DB::fetchrow("
			SELECT * FROM " . DB::$db_prefix . "_users
			WHERE 
				
				user_last_billing <= '".(time()-86400)."' AND 
				(
					user_group = '3' OR 
					(
						user_group = '4' AND user_billing != '1'
					)
				)  AND 
				user_billing_date >= '".(time()+86400)."'

		");

		foreach ($users as $row) {
			$app_tariff = siteBilling::get_tarif_user($row->user_group, $row->user_type_form, $row->user_billing_price);

			$coast = $app_tariff['price'][$row->user_billing_price]["price"]/$app_tariff['price'][$row->user_billing_price]["dest"];
			$balance = format_string_number($row->user_balance - $coast, true);

			DB::fetchrow("
				UPDATE " . DB::$db_prefix . "_users
				SET 
					user_balance = '".$balance."',
					user_last_billing = '".time()."'
				WHERE id = '".$row->id."'
			");

			DB::fetchrow("
				INSERT INTO " . DB::$db_prefix . "_users_history_pay
				SET 
					history_type = '2',
					history_owner = '".$row->id."',
					history_sum = '".format_string_number($coast, true)."',
					history_desc = '".twig::$lang['billing_pay_log']."',
					history_date = '".time()."'
			");
		}

		// снимаем тарифы у просроченных исполнителей
		DB::fetchrow("
			UPDATE " . DB::$db_prefix . "_users
			SET 
				user_billing = '0',
				user_billing_price = '0'
			WHERE 
				
				user_group = '3' AND 
				user_billing_date < '".time()."'
		");
		// снимаем тарифы у просроченных исполнителей
		// снимаем тарифы у просроченных заказчиков
		DB::fetchrow("
			UPDATE " . DB::$db_prefix . "_users
			SET 
				user_billing = '0',
				user_billing_price = '0'
			WHERE 
				user_group = '4' AND 
				user_billing != '1' AND 
				user_billing_date < '".time()."'
		");
		// снимаем тарифы у просроченных заказчиков
		
		echo __CLASS__ . ": success<br>";
		return true;
	}
}
?>
