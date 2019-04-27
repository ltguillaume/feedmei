require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		this.prev_feed = null;
		PluginHost.register(PluginHost.HOOK_FEED_SET_ACTIVE, (feed) => {
			if (feed[0] == this.prev_feed || (this.prev_feed == null && Feeds.getUnread(feed[0], feed[1]) < 1)) return;
			if (document.forms["toolbar-main"].view_mode.value == 'adaptive') {
				let order = Feeds.getUnread(feed[0], feed[1]) ? 'date_reverse' : 'default';
				if (order != document.forms["toolbar-main"].order_by.value) {
					dijit.getEnclosingWidget(document.forms["toolbar-main"].order_by).attr('value', order);
					Feeds.select(feed[0], feed[1]);
				}
			}
			this.prev_feed = feed[0];
		});
	});
});