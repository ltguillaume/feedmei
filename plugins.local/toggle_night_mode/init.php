<?php
class Toggle_Night_Mode extends Plugin {

	function about() {
		return array(1.0,
			"Allow night mode toggle for custom themes (use yourtheme_night.css as filename)",
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