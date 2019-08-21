<?php
/*	A simple script to update Tiny Tiny RSS (upload, extract and clean up).
 *	It takes a master.zip, i.e. a compressed snapshot from the master branch.
 *	Removal:
 *	- Removes useless folders: install, tests, feed-icons (moved to cache/feed-icons)
 *	- Removes useless files: .empty, .gitignore, *.less, *.map etc.
 *	- Removes plugins I don't use
 *	- Removes all language files, except for Dutch and English
 *	- Unlinks default.css.less mapping in default.css (prevents console error)
 *	Tweaks:
 *	- Adds <g> to the allowed elements in articles (for TorrentFreak articles)
 *	- Adds a 21px margin for when scrolling to next/previous article
 */

$password = '';	// sha256 hash
$keep_languages = ['en', 'nl'];
$keep_locale = ['nl_NL'];
$keep_plugins = ['af_readability', 'af_redditimgur', 'af_proxy_http', 'auth_internal', 'bookmarklets', 'note', 'share', 'vf_shared'];
$root = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/tt-rss';	// folder from extracted zip

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
	$contents = str_replace($find, $replace, $contents);
	file_put_contents($file, $contents);
}

if(isset($_POST['submit'])) {
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
		echo '<li>Contents have been extracted to <b>'. $target_path .'</b></li>';

		chdir($GLOBALS['root']);

		echo '<li>Unlinking .less source mapping in default.css</li>';
		fart('css/default.css', '/*# sourceMappingURL=default.css.map */', '');
		echo '<li>Adding &lt;g&gt; to allowed elements for TorrentFreak articles</li>';
		fart('include/functions.php', '$allowed_elements = array(', '$allowed_elements = array(\'g\', ');
		echo '<li>Adding margin for scrolling to articles</li>';
		fart('js/Article.js', 'ctr.scrollTop = e.offsetTop;', 'ctr.scrollTop = e.offsetTop - (App.getInitParam("cdm_expanded") ? 21 : 0);');

		echo '<li>Removing useless files...</li><ul>';
		foreach(glob('{,*,*/*,*/*/*,*/*/*/*,*/*/*/*/*}/{.empty,.gitignore,*.less,*.map}', GLOB_BRACE) as $file)	// No spaces after comma between {}!
			remove($file);
		remove('.editorconfig');
		remove('.gitignore');
		remove('.gitlab-ci.yml');
		remove('CONTRIBUTING.md');
		remove('COPYING');
		remove('README.md');
		remove('feed-icons');
		remove('install');
		remove('tests');

		echo '</ul><li>Removing unused plugins...</li><ul>';
		clean('plugins', $keep_plugins);
		
		echo '</ul><li>Removing unused languages (all but Dutch and English)...</li><ul>';
		clean('locale', $keep_locale);
		foreach(glob('{,*,*/*,*/*/*}/nls', GLOB_BRACE|GLOB_ONLYDIR) as $dir)
			clean($dir, $keep_languages);
		$keep_nls = ['colors.js', 'tt-rss-layer_ROOT.js', 'tt-rss-layer_en-us.js'];
		foreach ($keep_locale as $l)
			array_push($keep_nls, 'tt-rss-layer_'. str_replace('_', '-', strtolower($l)) .'.js');
		clean('lib/dojo/nls', $keep_nls, '.js');

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
			<p><input type='submit' value='Submit' name='submit'></p>
		</form>
	</body>
</html>