<?php
class Toggle_Night_Mode extends Plugin {

	private $host;

	function about() {
		return array(2.1,
			"Allow night mode toggle with [a N] for custom themes (use yourtheme_night.css as filename)",
			"ltGuillaume");
	}

	function init($host) {
		$this->host = $host;
		$host->add_hook($host::HOOK_HOTKEY_MAP, $this);
	}

	function hook_hotkey_map($hotkeys) {
		$hotkeys["a N"] = "toggle_night_mode";
		return $hotkeys;
	}

	function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	function api_version() {
		return 2;
	}

}