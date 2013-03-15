<?php
declare(encoding = 'UTF-8');

/**
 * Backend module to update database from local file.
 *
 * PHP version 5
 *
 * @category   Extensions
 * @package    TYPO3
 * @subpackage Module
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
define('TYPO3_MOD_PATH', '../typo3conf/ext/contexts_wurfl/mod/');
$BACK_PATH = '../../../../typo3/';

$MCONF['script']     = 'updateDbLocal.php';
$MCONF['access']     = 'admin';
$MCONF['name']       = 'tools_em';
$MCONF['workspaces'] = 'online';

require $BACK_PATH . 'init.php';

//make sure only admins do that
$BE_USER->modAccess($MCONF, 1);


$strApiPath = realpath(__DIR__ . '/../Library/wurfl-dbapi-1.4.4.0/');

require_once $strApiPath . '/TeraWurfl.php';
require_once $strApiPath . '/TeraWurflUtils/TeraWurflUpdater.php';

$wurfl   = new TeraWurfl();
$updater = new TeraWurflUpdater($wurfl, 'remote');

try {
	$available = $updater->isUpdateAvailable();
} catch(Exception $e) {
	$available = true;
}

// if (!$force_update && !$available) {
// 	header("Location: index.php?msg=".urlencode("Your WURFL data is already up to date. <a href=\"updatedb.php?source=remote&force=true\">Force update</a>")."&severity=notice");
// 	exit(0);
// }

try {
	$status = $updater->update();
} catch (TeraWurflUpdateDownloaderException $e) {
// 	$sf = ($updater->downloader->download_url == 'http://downloads.sourceforge.net/project/wurfl/WURFL/latest/wurfl-latest.zip');
// 	header("Location: index.php?msg=".urlencode($e->getMessage())."&severity=error&sf404=".$sf);
// 	exit(0);
}

if ($status) {
	echo "<strong>Database Update OK</strong><hr />";

	echo 'WURFL Version: ' . $updater->loader->version
		. ' (' . $updater->loader->last_updated . ')<br />';
	echo 'WURFL Devices: ' . $updater->loader->mainDevices . '<br/>';
	echo 'PATCH New Devices: '  .$updater->loader->patchAddedDevices . '<br/>';
	echo 'PATCH Merged Devices: ' . $updater->loader->patchMergedDevices . '<br/>';

	if (count($updater->loader->errors) > 0) {
		echo '<pre>';

		foreach ($updater->loader->errors as $error) {
			echo htmlspecialchars($error) . "\n";
		}

		echo '</pre>';
	}
} else {
	echo 'ERROR LOADING DATA!<br/>';
	echo 'Errors: <br/>' . "\n";
	echo '<pre>';

	foreach ($updater->loader->errors as $error) {
		echo htmlspecialchars($error) . "\n";
	}

	echo '</pre>';
}

?>
