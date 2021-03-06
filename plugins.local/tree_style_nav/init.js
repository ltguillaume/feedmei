require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_INIT_COMPLETE, () => {

			App.getInitParam("hotkeys")[1]["(38)"]  = "cursor_up";
			App.getInitParam("hotkeys")[1]["(40)"]  = "cursor_down";
			App.getInitParam("hotkeys")[1]["(37)"]  = "cursor_left";
			App.getInitParam("hotkeys")[1]["(39)"]  = "cursor_right";
			App.getInitParam("hotkeys")[1]["\r"]    = "cursor_enter";
			App.getInitParam("hotkeys")[1]["^(37)"] = "cursor_prev_article_noscroll";
			App.getInitParam("hotkeys")[1]["^(39)"] = "next_article_noscroll";
			App.getInitParam("hotkeys")[1]["(45)"]  = "toggle_unread";
			App.getInitParam("hotkeys")[1]["0"]     = "toggle_unread";

			App.hotkey_actions["cursor_up"]    = function () { Article.treeStyleNav("up") };
			App.hotkey_actions["cursor_down"]  = function () { Article.treeStyleNav("down") };
			App.hotkey_actions["cursor_left"]  = function () { Article.treeStyleNav("left") };
			App.hotkey_actions["cursor_right"] = function () { Article.treeStyleNav("right") };
			App.hotkey_actions["cursor_enter"] = function () { Article.treeStyleNav("enter") };
			App.hotkey_actions["cursor_prev_article_noscroll"] = function () { Headlines.move("prev", {force_previous: true}) };

			Article.treeStyleNav = function (key) {
				var hl = Headlines.getLoaded();
				if (hl.length == 0) return;

				var id = Headlines.getSelected();
				id = (id.length > 0 ? id[id.length - 1] : 0);

				if (id) {
					App.byId("RROW-"+ id).removeClassName("Selected");

					if (key == "up" && id == Article.getActive())
						return App.hotkey_actions["article_scroll_up"]();

					if (key == "down" && id == Article.getActive())
						return App.hotkey_actions["article_scroll_down"]();

					if (key == "left") {
						if (id == Article.getActive()) {
							App.byId("RROW-"+ id).removeClassName("active");
							App.byId("RROW-"+ id).addClassName("Selected");
							App.isCombinedMode() ? Article.cdmMoveToId(id) : false;
							return App.byId("headlines-frame").focus();
						} else {
							id = hl[0];
							App.byId("RROW-"+ id).addClassName("Selected");
							return App.isCombinedMode() ? Article.cdmMoveToId(id) : Headlines.scrollToArticleId(id);
						}
					}

					if (key == "right") {
						if (id == Article.getActive())
							return App.hotkey_actions["next_article_noscroll"]();
						else {
							App.byId("RROW-"+ id).removeClassName("Selected");
							return App.isCombinedMode() ? (Article.setActive(id), Article.cdmMoveToId(id)) : Article.view(id, false);
						}
					}

					if (key == "enter") {
						if (id == Article.getActive())
							return Article.openInNewWindow(id);
						else
							return App.isCombinedMode() ? (Article.setActive(id), Article.cdmMoveToId(id)) : Article.view(id, false);
					}

					App.byId("RROW-"+ id).removeClassName("Selected");
					if (App.isCombinedMode()) Article.cdmUnsetActive();
				} else if (key == "right")
					return App.hotkey_actions["next_article_noscroll"]();

				var index = hl.indexOf(id);
				if (key == "up")
					id = (index > 0 ? hl[index - 1] : (App.getInitParam("cdm_expanded") ? 0 : hl[hl.length - 1]));
				else
					id = (index < hl.length - 1 ? hl[index + 1] : hl[0]);

				var row = App.byId("RROW-"+ id);
				if (row) row.addClassName("Selected");

				App.getInitParam("cdm_expanded") ? Article.cdmMoveToId(id) : Headlines.scrollToArticleId(id);
			};

		});
	});
});