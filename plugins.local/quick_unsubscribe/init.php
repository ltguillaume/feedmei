<?php
class Quick_Unsubscribe extends Plugin {

	function about() {
		return array(1.2, "
			Speed up your cleaning rage with these hotkeys.
			M: Mark all articles in feed as read and open next feed,
			U: Unsubscribe from feed and open next feed
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