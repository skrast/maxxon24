<?php
class noaccess {
	function __construct() {
	}

	static function bild_page() {
		twig::assign('content', twig::fetch('no_access.tpl'));
	}
}
?>
