require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_INIT_COMPLETE, () => {

			App.getInitParam("hotkeys")[1]["(38)"]  = "cursor_up";
			App.getInitParam("hotkeys")[1]["(40)"]  = "cursor_down";
			App.getInitParam("hotkeys")[1]["(37)"]  = "cursor_left";
			App.getInitParam("hotkeys")[1]["(39)"]  = "cursor_right";
			App.getInitParam("hotkeys")[1]["\r"]    = "cursor_enter";
			App.getInitParam("hotkeys")[1]["^(37)"] = "prev_article_noscroll";
			App.getInitParam("hotkeys")[1]["^(39)"] = "next_article_noscroll";
			App.getInitParam("hotkeys")[1]["0"]     = "toggle_unread";

			App.hotkey_actions["cursor_up"]    = function () { Article.keyboardCursor("up") };
			App.hotkey_actions["cursor_down"]  = function () { Article.keyboardCursor("down") };
			App.hotkey_actions["cursor_left"]  = function () { Article.keyboardCursor("left") };
			App.hotkey_actions["cursor_right"] = function () { Article.keyboardCursor("right") };
			App.hotkey_actions["cursor_enter"] = function () { Article.keyboardCursor("enter") };

			Article.keyboardCursor = function (key) {
				var hl = Headlines.getLoaded();
				if (hl.length == 0) return;

				var id = Headlines.getSelected();
				id = (id.length > 0 ? id[id.length - 1] : 0);

				if (id) {
					$("RROW-"+ id).removeClassName("Selected");

					if (key == "up" && id == Article.getActive())
						return App.hotkey_actions["article_scroll_up"]();

					if (key == "down" && id == Article.getActive())
						return App.hotkey_actions["article_scroll_down"]();

					if (key == "left") {
						if (id == Article.getActive()) {
							$("RROW-"+ id).removeClassName("active").addClassName("Selected")
							App.isCombinedMode() ? Article.cdmScrollToId(id) : false;
							return $("headlines-frame").focus();
						} else {
							id = hl[0];
							$("RROW-"+ id).addClassName("Selected");
							return App.isCombinedMode() ? Article.cdmScrollToId(id) : Headlines.correctHeadlinesOffset(id);
						}
					}

					if (key == "right") {
						if (id == Article.getActive())
							return App.hotkey_actions["next_article_noscroll"]();
						else {
							$("RROW-"+ id).removeClassName("Selected");
							return App.isCombinedMode() ? (Article.setActive(id), Article.cdmScrollToId(id)) : Article.view(id, false);
						}
					}
					
					if (key == "enter") {
						if (id == Article.getActive())
							return Article.openInNewWindow(id);
						else
							return App.isCombinedMode() ? (Article.setActive(id), Article.cdmScrollToId(id)) : Article.view(id, false);
					}

					$("RROW-"+ id).removeClassName("Selected");
					if (App.isCombinedMode()) Article.cdmUnsetActive();
				} else if (key == "right")
					return App.hotkey_actions["next_article_noscroll"]();

				var index = hl.indexOf(id);
				if (key == "up")
					id = (index > 0 ? hl[index - 1] : (App.getInitParam("cdm_expanded") ? 0 : hl[hl.length - 1]));
				else
					id = (index < hl.length - 1 ? hl[index + 1] : hl[0]);

				var row = $("RROW-"+ id);
				if (row) row.addClassName("Selected");

				App.isCombinedMode() ? Article.cdmScrollToId(id) : Headlines.correctHeadlinesOffset(id);
			};

		});
	});
});