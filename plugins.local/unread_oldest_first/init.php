<?php
class Unread_Oldest_First extends Plugin {

	function about() {
		return array(2.0,
			"Changes the sort order to 'Oldest first' when the view mode is 'Adaptive' and there are unread articles, otherwise sets sort order back to 'Default'. Needs commit e981d52bda or newer.",
			"ltGuillaume");
	}

	function init($host) {}

	function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	function api_version() {
		return 2;
	}

}