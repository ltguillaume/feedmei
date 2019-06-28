require(['dojo/_base/kernel', 'dojo/ready'], function (dojo, ready) {
	ready(function () {
		PluginHost.register(PluginHost.HOOK_INIT_COMPLETE, () => {

			App.toggleNightMode = function () {
				const link = $("theme_css");

				if (link) {
					let user_theme = "";
					let user_css = "";
					let href = link.getAttribute("href");

					if (href.indexOf("themes.local") != -1) {
						href = href.substring(0, href.indexOf("?")).replace("themes.local/", "");
						if (href.indexOf("_night.css") == -1)
							user_theme = href.replace(".css", "_night.css");
						else
							user_theme = href.replace("_night.css", ".css");
						user_css = "themes.local/" + user_theme + "?" + Date.now();
						dojo.xhrGet({
							url: user_css,
							handleAs: 'text',
							sync: true,
							preventCache: true,
							error: function(data) { user_css = "" }
						});
					}

					if (user_css.length == 0) {
						if (href.indexOf("themes/night.css") == -1) {
							user_css = "themes/night.css?" + Date.now();
							user_theme = "night.css";
						} else {
							user_theme = "default.php";
							user_css = "css/default.css?" + Date.now();
						}
					}

					$("main").fade({duration: 0.5, afterFinish: () => {
						link.setAttribute("href", user_css);
						$("main").appear({duration: 0.5});
						xhrPost("backend.php", {op: "rpc", method: "setpref", key: "USER_CSS_THEME", value: user_theme});
					}});
				}
			};

		});
	});
});