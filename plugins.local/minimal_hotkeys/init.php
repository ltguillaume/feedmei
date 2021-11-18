<?php
class Minimal_Hotkeys extends Plugin {

	function about() {
		return array(1.6, "
			[ / ]: Feed prev/next,
			Left/right: Article prev/next,
			Up/down: Scroll,
			Enter: Open link,
			b: Toggle sidebar,
			o: Reverse order,
			p: Toggle published,
			r: Refresh feed,
			s: Toggle star,
			u: Toggle unread,
			x: Close article,
			g a|f|n|p|r|s|t: Go to All|Fresh|Night mode|Published|Read|Starred|Tags,
			v c|e|g|w: Toggle Combined|Expanded|Grid View|Widescreen view mode
			", "ltGuillaume");
	}

	function init($host) {
		$host->add_hook($host::HOOK_HOTKEY_MAP, $this);
	}

	function hook_hotkey_map($hotkeys) {
		unset($hotkeys);
		$hotkeys["["] = "prev_feed";
		$hotkeys["]"] = "next_feed";
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
		$hotkeys["x"] = "close_article";
		$hotkeys["g a"] = "goto_all";
		$hotkeys["g f"] = "goto_fresh";
		$hotkeys["g n"] = "toggle_night_mode";
		$hotkeys["g p"] = "goto_published";
		$hotkeys["g r"] = "goto_read";
		$hotkeys["g s"] = "goto_marked";
		$hotkeys["g t"] = "goto_tagcloud";
		$hotkeys["v c"] = "toggle_combined_mode";
		$hotkeys["v e"] = "toggle_cdm_expanded";
		$hotkeys["v g"] = "feed_toggle_grid";
		$hotkeys["v w"] = "toggle_widescreen";

		return $hotkeys;
	}

	function api_version() {
		return 2;
	}

}