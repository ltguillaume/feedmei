<?php
class Fresh_Oldest_First extends Plugin {
	private $host;

	function about() {
		return array(1.0,
			"Changes the default sort order upon switching feeds: 'Oldest first' for Fresh articles, 'Default' for others. Uncomment 2 lines in init.js to keep manually changed order across feeds, except for when going to/coming from Fresh articles.",
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