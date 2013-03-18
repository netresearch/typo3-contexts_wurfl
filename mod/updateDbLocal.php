<?php
declare(encoding = 'UTF-8');

/**
 * Backend module to update database from local file.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
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

// Import from local source
$import  = new Tx_Contexts_Wurfl_Api_Model_Import(TeraWurflUpdater::SOURCE_LOCAL);
$status  = $import->import();
$updater = $import->getUpdater();

if ($status) {
	echo 'Database Update OK<br />';

	echo 'WURFL Version: '
		. $updater->loader->version
		. ' (' . $updater->loader->last_updated . ')'
		. '<br />';
	echo 'WURFL Devices: '
		. $updater->loader->mainDevices . '<br />';
	echo 'PATCH New Devices: '
		. $updater->loader->patchAddedDevices . '<br />';
	echo 'PATCH Merged Devices: '
		. $updater->loader->patchMergedDevices . '<br />';

	if (count($updater->loader->errors) > 0) {
		echo 'Errors:<br />';

		foreach ($updater->loader->errors as $error) {
			echo htmlentities($error) . '<br />';
		}

		echo '<br />';
	}
} else {
	echo 'ERROR LOADING DATA!<br />';
	echo 'Errors:<br />';
	echo '<br />';

	foreach ($updater->loader->errors as $error) {
		echo htmlentities($error) . '<br />';
	}

	echo '<br />';
}

?>
