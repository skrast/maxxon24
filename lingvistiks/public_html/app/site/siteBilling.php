<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteBilling {

	static function work() {
		// title
		$page_info->page_title = twig::$lang['billing_name'];
		twig::assign('page_info', $page_info);
		// title
		
		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);

		$currency_list = get_book_for_essence(5);
		twig::assign('currency_list', $currency_list);

		$experience_list = get_book_for_essence(3);
		twig::assign('experience_list', $experience_list);

		if(!$profile_info || !$_SESSION['user_id'] || !in_array($profile_info->user_group, [3,4])) {
			site::error404();
		} else {

			$tarif_list = self::get_tarif_group($_SESSION['user_group'], $_SESSION['user_subgroup']);
			twig::assign('tarif_list', $tarif_list);

			$card_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_card
				WHERE card_owner = '". $profile_id ."'
				ORDER BY card_id DESC
			");
			foreach ($card_list as $row) {
				$row->card_number = mb_substr($row->card_number, mb_strlen($row->card_number)-4, 4);
			}

			// pager
			$num = DB::numrows("
				SELECT
				history_id
				FROM " . DB::$db_prefix . "_users_history_pay as us
				WHERE history_owner = '". $profile_id ."'
			");
			twig::assign('num', $num);
			if ($num > config::app('SYS_PEAR_PAGE'))
			{
				$page_nav = get_pagination(ceil($num / config::app('SYS_PEAR_PAGE')), get_current_page(), '<a href="' .ABS_PATH. 'billing/?page={s}">{t}</a>');
				twig::assign('page_nav', $page_nav);
			}
			// pager

			// pager
			$start = get_current_page() * config::app('SYS_PEAR_PAGE') - config::app('SYS_PEAR_PAGE');
			// pager
			
			$history_list = DB::fetchrow("
				SELECT *
				FROM " . DB::$db_prefix . "_users_history_pay
				WHERE history_owner = '". $profile_id ."'
				ORDER BY history_id DESC
				LIMIT " . $start . "," . config::app('SYS_PEAR_PAGE') . "
			");
			foreach ($history_list as $row) {
				$row->history_date = unix_to_date($row->history_date);
				$row->history_currency = $currency_list[$row->history_currency];
				$row->history_card_number = mb_substr($row->history_card_number, mb_strlen($row->history_card_number)-4, 4);
			}

			twig::assign('card_list', $card_list);
			twig::assign('history_list', $history_list);
			twig::assign('profile_info', $profile_info);

			$html = twig::fetch('frontend/chank/perfomens_col.tpl');
			twig::assign('perfomens_col', $html);

			twig::assign('user_group', $_SESSION['user_group']);
			twig::assign('user_subgroup', $_SESSION['user_subgroup']);

			$html = twig::fetch('frontend/chank/tariff_list.tpl');
			twig::assign('tariff_block', $html);


			if($tarif_list[$_REQUEST['change_tariff']] && $tarif_list[$_REQUEST['change_tariff']]["price"][$_REQUEST['change_price']]) {

				if($_SESSION['user_group'] == 4 && $_REQUEST['change_tariff'] == 1) {
					header('Location:'.ABS_PATH.'billing/');
					exit;
				}

				$tariff_price = $tarif_list[$_REQUEST['change_tariff']]["price"][$_REQUEST['change_price']];
				$tariff = $tarif_list[$_REQUEST['change_tariff']];

				// у нас хватает денег на переход на новый тариф
				if($profile_info->user_balance>=$tariff_price['price']) {
					$billing_date = time() + ($tarif_list[$_REQUEST['change_tariff']]["price"][$_REQUEST['change_price']]["dest"]*86400);
					DB::query("
						UPDATE
							" . DB::$db_prefix . "_users
						SET
							user_billing = '".(int)$_REQUEST['change_tariff']."',
							user_billing_price = '".(int)$_REQUEST['change_price']."',
							user_billing_date = '".$billing_date."'
						WHERE id = '".$profile_id."'
					");

					$_SESSION['user_billing'] = (int)$_REQUEST['change_tariff'];
					$_SESSION['user_billing_price'] = (int)$_REQUEST['change_price'];

					header('Location:'.ABS_PATH.'billing/');
					exit;
				} else {
					$tariff_price['price'] = $tariff_price['price']*(int)$_REQUEST['change_price'] - $profile_info->user_balance;
				}

				twig::assign('tariff', $tariff);
				twig::assign('tariff_price', $tariff_price);

				$html = twig::fetch('frontend/chank/tariff_change.tpl');
				twig::assign('tariff_block', $html);
			}

			// шаблон страницы
			twig::assign('content', twig::fetch('frontend/billing.tpl'));
		}
	}

	static function try_pay() {
		$change_pay_type = (int)$_REQUEST['change_pay_type'];

		$error = [];

		$tarif_list = self::get_tarif_group($_SESSION['user_group'], $_SESSION['user_subgroup']);
		if(!$change_pay_type || !$tarif_list[$_REQUEST['change_tariff']] || !$tarif_list[$_REQUEST['change_tariff']]["price"][$_REQUEST['change_price']]) {
			$error[] = twig::$lang["billing_pay_tariff_error"];
		}

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);
		if($profile_info->user_billing_last_change >= time()-3600*2) {
			header('Location:'.ABS_PATH.'billing/');
			exit;
		}

		if($_SESSION['user_group'] == 4 && $_REQUEST['change_tariff'] == 1) {
			header('Location:'.ABS_PATH.'billing/');
			exit;
		}

		switch ($change_pay_type) {
			case '1':

				/*$pay_card_number = implode("", $_REQUEST['pay_card_number']);
				if(mb_strlen($pay_card_number)<16 || preg_match("/[а-яёa-z]/iu", $pay_card_number)) {
					$error[] = twig::$lang["billing_pay_card_number_error"];
				}

				if(mb_strlen($_REQUEST['pay_card_owner'])<=1) {
					$error[] = twig::$lang["billing_pay_card_owner_error"];
				}

				$pay_card_exp = explode("/", $_REQUEST['pay_card_exp']);
				if(mb_strlen($pay_card_exp[0])<2 || mb_strlen($pay_card_exp[1])<2 || $pay_card_exp[1]<date("y") || ($pay_card_exp[1]==date("y") && $pay_card_exp[0]<date("m")) || $pay_card_exp[0]>12) {
					$error[] = twig::$lang["billing_pay_card_exp_error"];
				}

				if(mb_strlen($_REQUEST['pay_cvc'])<3) {
					$error[] = twig::$lang["billing_pay_cvc_error"];
				}*/

				if($_REQUEST['pay_sum_1']<=0) {
					$error[] = twig::$lang["billing_pay_sum_error"];

					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}

			break;

			case '2':
				if($_REQUEST['pay_sum_2']<=0) {
					$error[] = twig::$lang["billing_pay_sum_error"];

					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}
			break;

			case '3':
				if(mb_strlen($_REQUEST['pay_company_name'])<=1) {
					$error[] = twig::$lang["billing_pay_company_name_error"];

					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}
				if(mb_strlen($_REQUEST['pay_company_inn'])<=1) {
					$error[] = twig::$lang["billing_pay_company_inn_error"];

					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}
				/*if(mb_strlen($_REQUEST['pay_company_kpp'])<=1) {
					$error[] = twig::$lang["billing_pay_company_kpp_error"];
				}
				if(mb_strlen($_REQUEST['pay_company_address'])<=1) {
					$error[] = twig::$lang["billing_pay_company_address_error"];
				}*/

				if($_REQUEST['pay_sum_3']<=0) {
					$error[] = twig::$lang["billing_pay_sum_error"];

					$html = twig::fetch('chank/error_show.tpl');
					echo json_encode(array("respons"=>$html, "status"=>"error"));
					exit;
				}
			break;
		}

		if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		if(backend::isAjax()) {
			echo json_encode(array("upload"=>true, "status"=>"success"));
			exit;
		}

		$tarif_list = self::get_tarif_group($_SESSION['user_group'], $_SESSION['user_subgroup']);
	/*
		$tariff = $tarif_list[$_REQUEST['change_tariff']];
		$tariff_price = $tariff["price"][$_REQUEST['change_tariff']]["price"];
*/
		
		$tariff = $tarif_list[$_REQUEST['change_tariff']];
		$tariff_price = $tarif_list[$_REQUEST['change_tariff']]["price"][$_REQUEST['change_price']];
		
		
		twig::assign('tariff', $tariff);
		twig::assign('tariff_price', $tariff_price);

		switch ($change_pay_type) {
			case '1': // Сбер

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_sber
					WHERE owner_id = '".$_SESSION['user_id']."'
				");

				require BASE_DIR  . '/vendor/sberbank/vendor/autoload.php';

				$client = new \Voronkovich\SberbankAcquiring\Client([
					'userName' => 'maxxon24-api',
					'password' => 'Maxxon24<>1979',
				
					// A language code in ISO 639-1 format.
					// Use this option to set a language of error messages.
					'language' => 'ru',
				
					// A currency code in ISO 4217 format.
					// Use this option to set a currency used by default.
					'currency' => \Voronkovich\SberbankAcquiring\Currency::RUB,
				
					// An uri to send requests.
					// Use this option if you want to use the Sberbank's test server.
					'apiUri' => \Voronkovich\SberbankAcquiring\Client::API_URI,
				
					// An HTTP method to use in requests.
					// Must be "GET" or "POST" ("POST" is used by default).
					'httpMethod' => 'GET',
				
					// An HTTP client for sending requests.
					// Use this option when you don't want to use
					// a default HTTP client implementation distributed
					// with this package (for example, when you have'nt
					// a CURL extension installed in your server).
				]);

				$order_id = $_SESSION['user_id'] . "_" . time();
				$to_pay = ($tariff_price['price']*(int)$_REQUEST['change_price'])-$profile_info->user_balance;

				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_sber
					SET
						owner_id = '".$_SESSION['user_id']."',
						pay_sum = '".format_string_number($to_pay)."',
						pay_tarif = '".(int)$_REQUEST['change_tariff']."',
						pay_price = '".(int)$_REQUEST['change_price']."',
						pay_id = '".$order_id."'
				");

				$orderId     = trim($order_id);
				$orderAmount = $_REQUEST['pay_sum_2']*100;
				$returnUrl   = HOST_NAME . "/billing/pay/sber_return/?success=true";

				// You can pass additional parameters like a currency code and etc.
				$params['currency'] = \Voronkovich\SberbankAcquiring\Currency::RUB;
				$params['failUrl']  = HOST_NAME . "/billing/pay/sber_return/?success=false";

				$result = $client->registerOrder($orderId, $orderAmount, $returnUrl, $params);

				$paymentOrderId = $result['orderId'];
				$paymentFormUrl = $result['formUrl'];

				header('Location: ' . $paymentFormUrl);
				exit;

			break;

			case '2': // Палка

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_paypal
					WHERE owner_id = '".$_SESSION['user_id']."'
				");

				require BASE_DIR  . '/vendor/PayPal/vendor/autoload.php';

				$apiContext = new \PayPal\Rest\ApiContext(
					new \PayPal\Auth\OAuthTokenCredential(
						'AdvSPPDZSSUaDV0iSiCAihVv7kkF8Lvjy8dPuGZr7v7b5my2DLQKkjmawlvjqOuZJlxGRKUthnOuNtpZ',     // ClientID
						'ECZVWK-vpR4YPctX0Or-WHi2HqFbiTga9tfzMYvW8ynm5IuRZl0COf3RXYzyvXWt3aFgTsXQnrdpoWuq'      // ClientSecret
					)
				);

				$payer = new \PayPal\Api\Payer();
				$payer->setPaymentMethod('paypal');

				$amount = new \PayPal\Api\Amount();
				$amount->setTotal($_REQUEST['pay_sum_2']);
				$amount->setCurrency('RUB');

				$transaction = new \PayPal\Api\Transaction();
				$transaction->setAmount($amount);

				$redirectUrls = new \PayPal\Api\RedirectUrls();
				$redirectUrls->setReturnUrl(HOST_NAME . "/billing/pay/paypal_return/?success=true")
				->setCancelUrl(HOST_NAME . "/billing/pay/paypal_return/?success=false");

				$payment = new \PayPal\Api\Payment();
				$payment->setIntent('sale')
					->setPayer($payer)
					->setTransactions(array($transaction))
					->setRedirectUrls($redirectUrls);

				try {
					$payment->create($apiContext);

					$to_pay = ($tariff_price['price']*(int)$_REQUEST['change_price'])-$profile_info->user_balance;

					DB::query("
						INSERT INTO
							" . DB::$db_prefix . "_users_paypal
						SET
							owner_id = '".$_SESSION['user_id']."',
							pay_sum = '".format_string_number($to_pay)."',
							pay_tarif = '".(int)$_REQUEST['change_tariff']."',
							pay_price = '".(int)$_REQUEST['change_price']."',
							pay_id = '".$payment->getId()."'
					");

					header('Location:'. $payment->getApprovalLink());
					exit;
				}
				catch (\PayPal\Exception\PayPalConnectionException $ex) {
					// This will print the detailed information on the exception.
					//REALLY HELPFUL FOR DEBUGGING
					echo $ex->getData();
				}
			break;

			case '3': // Банк

				$date = date('d.m.Y');
				twig::assign('date', $date);

				$history_id = DB::single("
					SELECT history_id
					FROM " . DB::$db_prefix . "_users_history_pay
					ORDER BY history_id DESC
					LIMIT 1
				");
				twig::assign('history_id', $history_id);

				$doc_info->doc_title = twig::$lang["billing_pdf_order"] . " " . ($history_id+1) . " " .  twig::$lang["billing_pdf_order_from"] . " " . $date;
				$doc_info->doc_text = twig::fetch('frontend/chank/pay_pdf.tpl');

				$name_pdf = pdf::load($doc_info, $path);

			break;
		}

		exit;
	}

	static function sber_info() {
		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);
		if($profile_info->user_billing_last_change >= time()-3600*2) {
			header('Location:'.ABS_PATH.'billing/');
			exit;
		}


		require BASE_DIR  . '/vendor/sberbank/vendor/autoload.php';

		$client = new \Voronkovich\SberbankAcquiring\Client([
			'userName' => 'maxxon24-api',
			'password' => 'Maxxon24<>1979',
		
			// A language code in ISO 639-1 format.
			// Use this option to set a language of error messages.
			'language' => 'ru',
		
			// A currency code in ISO 4217 format.
			// Use this option to set a currency used by default.
			'currency' => \Voronkovich\SberbankAcquiring\Currency::RUB,
		
			// An uri to send requests.
			// Use this option if you want to use the Sberbank's test server.
			'apiUri' => \Voronkovich\SberbankAcquiring\Client::API_URI,
		
			// An HTTP method to use in requests.
			// Must be "GET" or "POST" ("POST" is used by default).
			'httpMethod' => 'GET',
		
			// An HTTP client for sending requests.
			// Use this option when you don't want to use
			// a default HTTP client implementation distributed
			// with this package (for example, when you have'nt
			// a CURL extension installed in your server).
		]);
		

		$orderId     = $_REQUEST['orderId'];
		$result = $client->getOrderStatusExtended($orderId);		

		if (\Voronkovich\SberbankAcquiring\OrderStatus::isDeposited($result['orderStatus'])) {


			$pay_info = DB::row("SELECT * FROM " . DB::$db_prefix . "_users_sber WHERE owner_id = '".$_SESSION['user_id']."' AND pay_id = '".clear_text($result['orderNumber'])."' ");
			if($pay_info) {

				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_history_pay
					SET 
						history_type = '1',
						history_pay_type = '1',
						history_owner = '".$_SESSION['user_id']."',
						history_sum = '".$pay_info->pay_sum."',
						history_date = '".time()."',
						history_desc = '" . twig::$lang["billing_history_sber"] . " " . $pay_info->pay_id ."'
				");

				$app_tariff = config::app('app_tariff');
				$billing_date = time() + ($app_tariff[$_SESSION['user_group']][$pay_info->pay_tarif]['price'][$pay_info->pay_price]["dest"]*86400);
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users
					SET 
						user_billing = '".$pay_info->pay_tarif."',
						user_billing_price = '".$pay_info->pay_price."',
						user_balance = user_balance+".$pay_info->pay_sum.",
						user_billing_date = '".$billing_date."',
						user_last_billing = '0'
					WHERE id = '".$_SESSION['user_id']."'
				");

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_sber
					WHERE owner_id = '".$_SESSION['user_id']."'
				");

				$_SESSION['user_billing'] = $pay_info->pay_tarif;
				$_SESSION['user_billing_price'] = $pay_info->pay_price;
			}
		}

		header('Location:'.ABS_PATH.'billing/');
		exit;
	}


	static function paypal_info() {

		$profile_id = (int)$_SESSION['user_id'];
		$profile_info = get_user_info($profile_id);
		if($profile_info->user_billing_last_change >= time()-3600*2) {
			header('Location:'.ABS_PATH.'billing/');
			exit;
		}

		if (isset($_GET['success']) && $_GET['success'] == 'true') {

			require BASE_DIR  . '/vendor/PayPal/vendor/autoload.php';
			$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					'AdvSPPDZSSUaDV0iSiCAihVv7kkF8Lvjy8dPuGZr7v7b5my2DLQKkjmawlvjqOuZJlxGRKUthnOuNtpZ',     // ClientID
					'ECZVWK-vpR4YPctX0Or-WHi2HqFbiTga9tfzMYvW8ynm5IuRZl0COf3RXYzyvXWt3aFgTsXQnrdpoWuq'      // ClientSecret
				)
			);
			
			$paymentId = $_GET['paymentId'];
			$payment = new \PayPal\Api\Payment();
			$payment = $payment::get($paymentId, $apiContext);

			$execution = new \PayPal\Api\PaymentExecution();
			$execution->setPayerId($_GET['PayerID']);

			try {
				$result = $payment->execute($execution, $apiContext);
				try {
					$payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
				} catch (Exception $ex) {
					//dd($ex);
				}
			} catch (Exception $ex) {
				//dd($ex);
			}

			$pay_info = DB::row("
				SELECT * FROM
					" . DB::$db_prefix . "_users_paypal
				WHERE
					owner_id = '".$_SESSION['user_id']."' AND pay_id = '".$payment->getId()."'
			");

			if($pay_info && $payment->getId()) {

				DB::query("
					INSERT INTO
						" . DB::$db_prefix . "_users_history_pay
					SET 
						history_type = '1',
						history_pay_type = '2',
						history_owner = '".$_SESSION['user_id']."',
						history_sum = '".$pay_info->pay_sum."',
						history_date = '".time()."',
						history_desc = '" . twig::$lang["billing_history_paypal"] . " " . $payment->getId()."'
				");

				$app_tariff = config::app('app_tariff');
				$billing_date = time() + ($app_tariff[$_SESSION['user_group']][$pay_info->pay_tarif]['price'][$pay_info->pay_price]["dest"]*86400);
				DB::query("
					UPDATE
						" . DB::$db_prefix . "_users
					SET 
						user_billing = '".$pay_info->pay_tarif."',
						user_billing_price = '".$pay_info->pay_price."',
						user_balance = user_balance+".$pay_info->pay_sum.",
						user_billing_date = '".$billing_date."',
						user_last_billing = '0'
					WHERE id = '".$_SESSION['user_id']."'
				");

				DB::query("
					DELETE FROM
						" . DB::$db_prefix . "_users_paypal
					WHERE owner_id = '".$_SESSION['user_id']."'
				");

				$_SESSION['user_billing'] = $pay_info->pay_tarif;
				$_SESSION['user_billing_price'] = $pay_info->pay_price;
			}



		} else {
			//echo "User Cancelled the Approval";
		}

		//dd($payment);

		header('Location:'.ABS_PATH.'billing/');
		exit;
	}

	

	static function new_pay() {
		if(!in_array($_SESSION['user_group'], [3,4])) {
			site::error404();
		}

		$error = [];
		$billing_sum = format_string_number($_REQUEST['billing_sum'], true);

		try {
			v::length(1, null)->assert($billing_sum);
		} catch(ValidationException $exception) {
			$error[] = twig::$lang["billing_sum_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		$card_id = (int)$_REQUEST['billing_card'];
		if($card_id) {
			$card_info = DB::row("
				SELECT *
				FROM " . DB::$db_prefix . "_users_card
				WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_id = '".$card_id."'
			");

			if(!$card_info) {
				$error[] = twig::$lang["billing_card_error"];

				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}
		} else {
			$error[] = twig::$lang["billing_card_error"];

			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		/*$currency_list = get_book_for_essence(5);
		if(!$currency_list[(int)$_REQUEST['billing_currency']]) {
			$error[] = twig::$lang["billing_currency_error"];
		}*/

		if($error) {
			twig::assign('error', $error);
			$html = twig::fetch('chank/error_show.tpl');
			echo json_encode(array("respons"=>$html, "status"=>"error"));
			exit;
		}

		DB::query("
			INSERT INTO " . DB::$db_prefix . "_users_history_pay
			SET
			 	history_owner = '". $_SESSION['user_id'] ."',
				history_card_number = '".$card_info->card_number."',
				history_sum = '".$billing_sum."',
				history_date = '".time()."'
		");

		echo json_encode(array("ref"=>HOST_NAME.'/billing/', "status"=>"success"));
		exit;
	}

	static function card_delete() {
		$card_id = (int)$_REQUEST['card_id'];

		if(!in_array($_SESSION['user_group'], [3,4])) {
			site::error404();
		}

		$card_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_card
			WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_id = '".$card_id."'
		");

		if($card_info) {

			DB::query("
				DELETE FROM " . DB::$db_prefix . "_users_card
				WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_id = '".$card_id."'
			");

			echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
		}

		exit;
	}

	static function card_work() {
		$card_id = (int)$_REQUEST['void_id'];

		if(!in_array($_SESSION['user_group'], [3,4])) {
			site::error404();
		}

		$card_info = DB::row("
			SELECT *
			FROM " . DB::$db_prefix . "_users_card
			WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_id = '".$card_id."'
		");

		$title = twig::$lang['billing_card_add'];
		if($card_info) {

			$card_info->card_number = str_split($card_info->card_number, 4);

			twig::assign('card_info', $card_info);
			$title = twig::$lang['billing_card_edit'];
		}

		if($_REQUEST['save']) {
			$error = [];

			$card_number = $_REQUEST['card_number'];
			$card_number_str = "";
			$card_error = 0;
			foreach ($card_number as $row) {
				try {
					v::length(4, 4)->assert($row);
					$card_number_str.=$row;
				} catch(ValidationException $exception) {
					$card_error = 1;
				}
			}
			try {
				v::length(16, 16)->assert((int)$card_number_str);
			} catch(ValidationException $exception) {
				$card_error = 1;
			}
			if($card_error == 1) {
				$error[] = twig::$lang["card_number_error"];
			}

			try {
				v::length(config::app('app_min_strlen'), null)->assert(clear_text($_REQUEST['card_owner_name']));
				$card_number_str.=$card_number;
			} catch(ValidationException $exception) {
				$error[] = twig::$lang["card_owner_name_error"];
			}

			try {
				v::length(5, 5)->assert(clear_text($_REQUEST['card_date']));
				$card_number_str.=$card_number;
			} catch(ValidationException $exception) {
				$error[] = twig::$lang["card_date_error"];
			}

			$check_card = DB::row("
				SELECT * FROM
					" . DB::$db_prefix . "_users_card
				WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_number = '".(int)$card_number_str."'
			");
			if($check_card) {
				$error[] = twig::$lang["card_copy_error"];
			}

			if($error) {
				twig::assign('error', $error);
				$html = twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			if($card_info) {
				DB::query("
					UPDATE
					" . DB::$db_prefix . "_users_card
					SET
						card_number = '".(int)$card_number_str."',
						card_owner_name = '".clear_text($_REQUEST['card_owner_name'])."',
						card_date = '".clear_text($_REQUEST['card_date'])."'
					WHERE card_owner = '". $_SESSION['user_id'] ."' AND card_id = '".$card_id."'
				");
			} else {
				DB::query("
					INSERT INTO
					" . DB::$db_prefix . "_users_card
					SET
						card_number = '".(int)$card_number_str."',
						card_owner_name = '".clear_text($_REQUEST['card_owner_name'])."',
						card_date = '".clear_text($_REQUEST['card_date'])."',
					 	card_owner = '". $_SESSION['user_id'] ."'
				");
			}

			echo json_encode(array("ref"=>HOST_NAME.'/billing/', "status"=>"success"));
			exit;
		}

		$html = twig::fetch('frontend/chank/card_work.tpl');
		echo json_encode(array("html"=>$html, "title"=>$title, "status"=>"success"));
		exit;
	}


	static function get_tarif_group($user_group, $user_type) {
		return config::app("app_tariff")[$user_group][$user_type];
	}

	static function get_tarif_user($user_group, $user_type, $user_billing) {
		return config::app("app_tariff")[$user_group][$user_type][$user_billing];
	}

}
?>
