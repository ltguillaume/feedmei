<?php
class Quick_Unsubscribe extends Plugin {

	private $host;

	function about() {
		return array(1.1, "
			Speed up your cleaning rage with these hotkeys.
			M: Mark all articles in feed as read and open next feed,
			u: Unsubscribe from feed and open next feed
			", "ltGuillaume");
	}

	function init($host) {}

	function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	function api_version() {
		return 2;
	}

}