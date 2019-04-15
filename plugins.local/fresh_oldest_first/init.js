require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_FEED_SET_ACTIVE, (feed) => {
			if (feed[0] == this.last_feed) return;
//			if (feed[0] == -3 || this.last_feed == -3) {	// Uncomment to keep manually changed order across feeds
				let order = feed[0] == -3 ? 'date_reverse' : 'default';
				if (order != dijit.getEnclosingWidget(document.forms["toolbar-main"].order_by).attr('value')) {
					Headlines.reverse();
					console.log('Fresh_Oldest_First: reversing headlines for feed '+ feed[0]);
				}
//			}
			this.last_feed = feed[0];
		});
	});
});