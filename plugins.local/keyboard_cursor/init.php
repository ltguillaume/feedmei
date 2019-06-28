<?php
class Keyboard_Cursor extends Plugin {

	private $host;

	function about() {
		return array(1.0, "
			Use cursor keys for quick tree-style article navigation.
			(Key: when no article active | when article active)
			Up/down: select article row | scroll active article,
			Left: read previous article | close/deactivate active article,
			Right: read selected article | read next article
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