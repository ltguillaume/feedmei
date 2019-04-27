<?php
class Unread_Oldest_First extends Plugin {
	private $host;

	function about() {
		return array(1.1,
			"Changes the sort order to 'Oldest first' when the view mode is 'Adaptive' and there are unread articles, otherwise sets sort order back to 'Default'.",
			"ltGuillaume");
	}

	function init($host) {
		$this->host = $host;
	}

	function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	function api_version() {
		return 2;
	}

}