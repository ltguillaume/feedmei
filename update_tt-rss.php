<?php
/*	A simple script to update Tiny Tiny RSS (upload, extract and clean up).
 *	It takes a master.zip, i.e. a compressed snapshot from the master branch.
 *	Tweaks:
 *	- Adds <g>, <main> and <article> to the allowed elements in articles
 *	- Adds a 19px margin for when scrolling to next/previous article
 *  - Change the way article hashes are calculated:
 *    causes the option "Mark updated articles unread" to be triggered only when
 *    the article contents have been updated, not when metadata have changed or
 *    after you have changed your plugin configuration
 *    WARNING: THIS WILL RECALCULATE HASHES AND COULD MARK MANY ARTICLES AS UNREAD
 *  - Modify line_scroll_offset for scrolling with arrow up/down
 *	Removal:
 *	- Removes useless folders: install, tests, feed-icons (moved to cache/feed-icons)
 *	- Removes useless files: .empty, .gitignore, *.less, *.map etc.
 *	- Removes all language files, except for those set in $keep_langs / $keep_locale
 *	- Removes plugins, except for those set in $keep_plugins
 *	- Unlinks light.css.less mapping in light.css (prevents console error)
 */

$password     = ''; // sha256 hash
// Tweaks
$alt_hash     = FALSE; // use FALSE to disable changes
$line_offset  = 240;  // use FALSE to disable changes
// Removal
$keep_langs   = ['en', 'nl']; // use FALSE to disable
$keep_locale  = ['nl_NL'];    // use FALSE to disable
$keep_plugins = ['af_readability', 'af_redditimgur', 'af_proxy_http', 'auth_internal', 'bookmarklets', 'note', 'share', 'vf_shared']; // use FALSE to disable
$root         = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/tt-rss'; // folder from extracted zip

function remove($path, $key = null, $print = true) {
	chdir($GLOBALS['root']);
	if (empty($path) || !file_exists($path)) {
		echo "<li>$path <b>does not exist</b></li>";
		return;
	}
	$error = false;
	if (is_dir($path)) {
		$dir = glob(($path = $path .'/') .'*');
		if (array_walk($dir, __FUNCTION__, false) == @rmdir($path))
			$error = is_dir($path);
	} else if (!unlink($path))
			$error = true;
	if ($error) echo "<li>$path <b>could not be deleted</b></li>";
	else if ($print) echo "<li>$path</li>";
}

function clean($dir = false, $keep, $ext = false) {
	if ($dir) chdir($GLOBALS['root'] .'/'. $dir);
	$contents = glob('*'. ($ext ? $ext : ''), ($ext ? null : GLOB_ONLYDIR));
	foreach(array_diff($contents, $keep) as $path)
		remove($dir .'/'. $path);
}

function fart($file, $find, $replace) {
	chdir($GLOBALS['root']);
	$contents = file_get_contents($file);
	$newcontents = str_replace($find, $replace, $contents);
	if ($newcontents == $contents)
		echo ' - <b>FAILED: No changes made.</b>';
	else if (!file_put_contents($file, $newcontents))
		echo ' - <b>FAILED: Could not save changes.</b>';
}

if (isset($_POST['submit'])) {
	echo '<style>ul{columns:3} ul>li{font-size:.9rem}</style>';
	if (!empty($password) && (!isset($_POST['password']) || hash('sha256', $_POST['password']) != $password))
		die('Password incorrect');
	if (isset($_POST['download'])) {
		echo '<li>Downloading latest commit from master branch...</li>';
		$target_file = '_tt-rss-master.zip';
		$master = fopen('https://git.tt-rss.org/fox/tt-rss/archive/master.zip', 'r');
		if (!file_put_contents($target_file, $master))
			die('Download failed');
	} else {
		if (empty($_FILES['zip']['name'])) die('No file uploaded');
		echo '<li>Uploaded to temp file <b>'. $_FILES['zip']['tmp_name'] .'</b></li>';
		$target_file = basename($_FILES['zip']['name']);
		if (move_uploaded_file($_FILES['zip']['tmp_name'], $target_file))
			echo '<li>File <b>'. $target_file .'</b> has been uploaded</li>';
		else die('Error while moving upload to '. $target_file);
	}
	$target_path = pathinfo(realpath($target_file), PATHINFO_DIRNAME);
	$zip = new ZipArchive;
	$res = $zip->open($target_file);
	if ($res === true) {
		$zip->extractTo($target_path);
		$zip->close();
		unlink($target_file);
		echo '<li>Contents have been extracted to <b>'. $target_path .'</b></li>';

		chdir($GLOBALS['root']);

		echo '<li>Unlinking .less source mapping in light.css</li>';
		fart('themes/light.css', '/*# sourceMappingURL=light.css.map */', '');
		echo '<li>Adding &lt;g&gt;, &lt;main&gt; and &lt;article&gt; to allowed elements for TorrentFreak and New Scientist articles</li>';
		fart('include/functions.php', '$allowed_elements = array(', '/* Changed by tt-rss updater script */ $allowed_elements = array(\'g\', \'main\', \'article\', ');
		echo '<li>Adding margin for scrolling to articles</li>';
		fart('js/Article.js', 'ctr.scrollTop = row.offsetTop;', '/* Changed by tt-rss updater script */ ctr.scrollTop = row.offsetTop - (App.getInitParam("cdm_expanded") ? 18 : 0);');

		if ($line_offset) {
			echo '<li>Changing line offset for scrolling with cursor keys to '. $line_offset .'px</li>';
			fart('js/Headlines.js', 'line_scroll_offset: 120', '/* Changed by tt-rss updater script */ line_scroll_offset: '. $line_offset);
		} else echo '<li><b>Skipping</b> line offset change</li>';
		
		if ($alt_hash) {
			echo '<li>Excluding all fields apart from content in article hash calculation.</li>';
			fart('classes/rssutils.php', 'calculate_article_hash($article, $pluginhost) {',
				'calculate_article_hash($article, $pluginhost) { /* Changed by tt-rss updater script */ $v = $article["content"]; return sha1(strip_tags(is_array($v) ? implode(",", $v) : $v));');
		} else echo '<li><b>Skipping</b> article hash change: plugin names list is still used to calculate hash.</li>';

		echo '<li>Removing useless files...</li><ul>';
		foreach(glob('{,*,*/*,*/*/*,*/*/*/*,*/*/*/*/*}/{.empty,.gitignore,*.less,*.map}', GLOB_BRACE) as $file) // No spaces after comma between {}!
			remove($file);
		remove('.editorconfig');
		remove('.gitignore');
		remove('CONTRIBUTING.md');
		remove('COPYING');
		remove('README.md');
		remove('feed-icons');
		remove('install');
		remove('utils');

		if (is_array($keep_langs)) {
			echo '</ul><li>Removing unused languages (all but '. implode(', ', $keep_locale) .', '. implode(', ', $keep_langs) .')...</li><ul>';
			clean('locale', $keep_locale);
			foreach(glob('{,*,*/*,*/*/*}/nls', GLOB_BRACE|GLOB_ONLYDIR) as $dir)
				clean($dir, $keep_langs);
			$keep_nls = ['colors.js', 'tt-rss-layer_ROOT.js', 'tt-rss-layer_en-us.js'];
		} else echo '<li><b>Skipping</b> language removal</li>';
		if (is_array($keep_locale)) {
			foreach ($keep_locale as $l)
				array_push($keep_nls, 'tt-rss-layer_'. str_replace('_', '-', strtolower($l)) .'.js');
			clean('lib/dojo/nls', $keep_nls, '.js');
		} else echo '<li><b>Skipping</b> locale removal</li>';

		if (is_array($keep_plugins)) {
			echo '</ul><li>Removing unused plugins...</li><ul>';
			clean('plugins', $keep_plugins);
		} else echo '<li><b>Skipping</b> plugins removal</li>';

		echo '</ul><li>Moving files into place...</li><ul>';
		print_r(shell_exec('cp -Rf '. $GLOBALS['root'] .'/* '. pathinfo(__FILE__, PATHINFO_DIRNAME) .'/'));
		print_r(shell_exec('rm -r '. $GLOBALS['root']));
		
		echo '</ul>Done.';
	} else die('Could not open file for extraction');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<body>
		<form action='<?=basename(__FILE__)?>' method='post' enctype='multipart/form-data'>
			<p><input type='checkbox' name='download' id='download' checked> Download latest commit from master branch</p>
			<p>Or upload zip: <input type='file' name='zip' onclick="download.checked = 0"></p>
			<?php if (!empty($password)): ?><p>Enter password: <input type='password' name='password' autofocus></p><?php endif ?>
			<p><input type='submit' value='Submit' name='submit' onclick="this.value='Please wait...'"></p>
		</form>
	</body>
</html>