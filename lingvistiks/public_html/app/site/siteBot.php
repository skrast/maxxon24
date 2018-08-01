<?php
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class siteBot {
	static $bot_id = 30;

	static function velcom($to) {
		$message = twig::fetch('frontend/message/velcom.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 1]);
	}

	/*static function subscribe_lost($to) {
		$message = twig::fetch('frontend/message/subscribe_lost.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message);
	}

	static function order_perfomens_done($to) {
		$message = twig::fetch('frontend/message/order_perfomens_done.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message);
	}*/


	static function order_perfomens_accept($to, $respons_id) { // для заказчика, исполнитель взял заказ в работу
		$message = twig::fetch('frontend/message/order_perfomens_accept.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 2, "message_respons" => $respons_id]);
	}

	static function order_owner_perfomens_accept($to, $respons_id) { // для исполнителя, исполнитель подтвержден
		$message = twig::fetch('frontend/message/order_owner_perfomens_accept.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 3, "message_respons" => $respons_id]);
	}

	static function order_owner_change_perfomens($to, $respons_id) { // для исполнителя, заказчик предложил заказ
		$message = twig::fetch('frontend/message/order_owner_change_perfomens.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 4, "message_respons" => $respons_id]);
	}

	static function order_perfomens_owner_accept($to, $respons_id) { // для заказчика, исполнитель согласился
		$message = twig::fetch('frontend/message/order_perfomens_owner_accept.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 5, "message_respons" => $respons_id]);
	}

	static function jobs_perfomens_access($to, $respons_id) { // для заказчика, исполнитель откликнулся на вакансию
		$message = twig::fetch('frontend/message/jobs_perfomens_access.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 6, "message_respons" => $respons_id]);
	}

	static function jobs_owner_perfomens_accept($to, $respons_id) { // для исполнителя, заказчик согласился на вакансию
		$message = twig::fetch('frontend/message/jobs_owner_perfomens_accept.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 7, "message_respons" => $respons_id]);
	}

	static function resume_owner_access($to, $respons_id) { // для исполнителя, заказчик согласился откликнулся на резюме
		$message = twig::fetch('frontend/message/resume_owner_access.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 8, "message_respons" => $respons_id]);
	}

	static function resume_owner_perfomens_accept($to, $respons_id) { // для заказчика, заказчик ответил на предложение по резюме
		$message = twig::fetch('frontend/message/resume_owner_perfomens_accept.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 9, "message_respons" => $respons_id]);
	}

	static function order_owner_close($to, $respons_id) {
		$message = twig::fetch('frontend/message/order_owner_perfomens_close.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 10, "message_respons" => $respons_id]);
	}

	static function order_perfomens_close($to, $respons_id) {
		$message = twig::fetch('frontend/message/order_owner_perfomens_close.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 11, "message_respons" => $respons_id]);
	}

	static function document_invite($to, $respons_id) {
		$message = twig::fetch('frontend/message/document_invite.tpl');
		siteMessage::message_add(self::$bot_id, $to, $message, ["type" => 14, "document_id" => $respons_id]);
	}



}

?>
