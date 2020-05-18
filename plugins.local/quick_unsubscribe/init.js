require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_INIT_COMPLETE, () => {

			App.getInitParam("hotkeys")[1]["M"] = "mark_all_read";
			App.getInitParam("hotkeys")[1]["u"] = "unsubscribe";

			App.hotkey_actions["mark_all_read"]   = function () {
				const rv = dijit.byId("feedTree").getNextFeed(
					Feeds.getActive(), Feeds.activeIsCat());

				App.hotkey_actions["feed_catchup"]();
				
				if (rv) Feeds.open({feed: rv[0], is_cat: rv[1], delayed: true});
			};

			App.hotkey_actions["unsubscribe"]   = function () {
				const rv = dijit.byId("feedTree").getNextFeed(
					Feeds.getActive(), Feeds.activeIsCat());

				App.onActionSelected('qmcRemoveFeed');

				if (rv) Feeds.open({feed: rv[0], is_cat: rv[1], delayed: true});
			};

		});
	});
});