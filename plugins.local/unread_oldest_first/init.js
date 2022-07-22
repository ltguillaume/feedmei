require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		this.prev_feed = null;

		function setOrder(feed, isCat) {
			console.log('unread_oldest_first: triggered setOrder(['+ feed +', '+ isCat +']) | Unread = '+ Feeds.getUnread(feed, isCat));
			const toolbar = dijit.byId("toolbar-main");
			if (toolbar.getValues().view_mode == 'adaptive') {
				let order_by = Feeds.getUnread(feed, isCat) > 0 ? 'date_reverse' : 'default';
				if (order_by != toolbar.getValues().order_by) {
					console.log('unread_oldest_first: setting order to '+ order_by);
					toolbar.setValues({order_by: order_by});
				}
			}
			this.prev_feed = feed;
		}

		PluginHost.register(PluginHost.HOOK_FEED_SET_ACTIVE, (feed) => {

			var isCat = feed[1], feed = feed[0];

			console.log('unread_oldest_first: HOOK_FEED_SET_ACTIVE | Feed '+ feed +' | Unread = '+ Feeds.getUnread(feed, isCat) +' | Current order = '+ document.forms["toolbar-main"].order_by.value);
			if (this.prev_feed == null && Feeds.getUnread(feed, isCat) == -1) {
				var countersHook = function() {
					console.log('unread_oldest_first: HOOK_COUNTERS_PROCESSED | Feed '+ feed +' | Unread = '+ Feeds.getUnread(feed, isCat));
					if (this.prev_feed == 'hooked')
						setOrder([Feeds.getActive(), Feeds.activeIsCat()]);
					PluginHost.unregister(PluginHost.HOOK_COUNTERS_PROCESSED, countersHook);
				}
				PluginHost.register(PluginHost.HOOK_COUNTERS_PROCESSED, countersHook);
				return this.prev_feed = 'hooked';
			}

			if (feed != this.prev_feed)
				setOrder(feed, isCat);

		});
	});
});