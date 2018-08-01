<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class ajax {
	static function add_reklama() {
		// справочник Сферы деятельности для рекламы
		$theme_list = get_book_for_essence(9);
		twig::assign('theme_list', $theme_list);
		// справочник Сферы деятельности для рекламы

		if($_REQUEST['save']) {
			$error = [];

			$adv_theme = (int)$_REQUEST['adv_theme'];
			$adv_message = clear_text($_REQUEST['adv_message']);

			try {
			    v::length(\config::app('app_min_strlen'), null)->assert($adv_message);
			} catch(ValidationException $exception) {
			    $error[] = \twig::$lang["page_error_adv_message"];
			}

			try {
				v::key($adv_theme)->assert($theme_list);
			} catch(ValidationException $exception) {
				$error[] = \twig::$lang["page_error_adv_theme"];
			}

			if($error) {
				\twig::assign('error', $error);
				$html = \twig::fetch('chank/error_show.tpl');
				echo json_encode(array("respons"=>$html, "status"=>"error"));
				exit;
			}

			$user_info = get_user_info(1);
			$email_body = $_SESSION['user_name']." (".$_SESSION['user_id'].")<br>".$theme_list[$adv_theme]->title . ":<br> ".$adv_message;
			sendmail::sendmail_bilder($user_info, twig::$lang['page_advert'], $email_body, true);

			echo json_encode(array("respons"=>twig::$lang['form_save_success'], "status"=>"success"));
			exit;
		}

		$html = twig::fetch('frontend/chank/add_reklama.tpl');
		echo json_encode(array("title"=>twig::$lang["page_advert"],"html"=>$html, "status"=>"success"));
		exit;
	}
}
?>
