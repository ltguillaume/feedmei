require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_INIT_COMPLETE, () => {

			App.hotkey_actions["toggle_night_mode"] = function () {
				App.toggleNightMode()
			};

			App.toggleNightMode = function () {
				const link = $("theme_css");
				if (!link) return;

				let user_css = false;
				let href = link.getAttribute("href").split("?")[0];
				let userCSS = function(url, or = false) {
					dojo.xhrGet({
						url: url,
						handleAs: 'text',
						sync: true,
						preventCache: true,
						load: function() { user_css = url },
						error: function() { user_css = or }
					});
				}

				if (href.indexOf("themes.local/") > -1) {
					if (href.indexOf("_night.css") > -1)
						userCSS(href.replace("_night.css", ".css"));
					else
						userCSS(href.replace(".css", "_night.css"));
				}	else if (href.indexOf("/night.css") > -1)
						userCSS("themes/light.css", "css/default.css");
					else
						userCSS("themes/night.css");

				if (user_css)
					$("main").fade({duration: .4, afterFinish: () => {
						link.setAttribute("href", user_css);
 						$("main").appear({duration: .8});
 						xhrPost("backend.php", {
							op: "rpc",
							method: "setpref",
							key: "USER_CSS_THEME",
							value: user_css.split("/")[1] });
					}});
			};

		});
	});
});