<?php
class Minimal_Hotkeys extends Plugin {
	private $host;

	function about() {
		return array(1.2, "
			Left/right: prev/next,
			Up/down: scroll,
			Enter: open link,
			B: toggle sidebar,
			O: reverse order,
			P: toggle published,
			R: refresh feed,
			S: toggle star,
			U: toggle unread,
			G A|F|P|S|T: Go to all|fresh|published|starred|tags
			", "ltGuillaume");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_HOTKEY_MAP, $this);
	}

	function hook_hotkey_map() {

		$hotkeys["(37)|Left"] = "prev_article_noscroll";
		$hotkeys["(39)|Right"] = "next_article_noscroll";
		$hotkeys["(38)|Up"] = "article_scroll_up";
		$hotkeys["(40)|Down"] = "article_scroll_down";
		$hotkeys["\r|Enter"] = "open_in_new_window";
		$hotkeys["b"] = "collapse_sidebar";
		$hotkeys["o"] = "feed_reverse";
		$hotkeys["p"] = "toggle_publ";
		$hotkeys["r"] = "feed_refresh";
		$hotkeys["s"] = "toggle_mark";
		$hotkeys["u"] = "toggle_unread";
		$hotkeys["g a"] = "goto_all";
		$hotkeys["g f"] = "goto_fresh";
		$hotkeys["g p"] = "goto_published";
		$hotkeys["g s"] = "goto_marked";
		$hotkeys["g t"] = "goto_tagcloud";

		return $hotkeys;
	}

	function api_version() {
		return 2;
	}

}