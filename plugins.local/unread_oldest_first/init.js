require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		this.prev_feed = null;

		function setOrder(feed) {
			if (document.forms["toolbar-main"].view_mode.value == 'adaptive') {
				let order = Feeds.getUnread(feed[0], feed[1]) > 0 ? 'date_reverse' : 'default';
				if (order != document.forms["toolbar-main"].order_by.value)
					dijit.getEnclosingWidget(document.forms["toolbar-main"].order_by).attr('value', order);
			}
			this.prev_feed = feed[0];
		}

		PluginHost.register(PluginHost.HOOK_FEED_SET_ACTIVE, (feed) => {

			if (this.prev_feed == null && Feeds.getUnread(feed[0], feed[1]) == -1) {
				var countersHook = function() {
					if (this.prev_feed == 'hooked')
						setOrder([Feeds.getActive(), Feeds.activeIsCat()]);
					PluginHost.unregister(PluginHost.HOOK_COUNTERS_PROCESSED, countersHook);
				}
				PluginHost.register(PluginHost.HOOK_COUNTERS_PROCESSED, countersHook);
				return this.prev_feed = 'hooked';
			}

			if (feed[0] != this.prev_feed)
				setOrder(feed);

		});
	});
});