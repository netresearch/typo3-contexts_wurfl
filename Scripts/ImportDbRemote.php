<?php
declare(encoding = 'UTF-8');

/**
 * Backend module to update database from remote file.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

// Set required module path
define('TYPO3_MOD_PATH', '../typo3conf/ext/contexts_wurfl/Scripts/');

require '../../../../typo3/init.php';

// Make sure only admins do that
$BE_USER->modAccess(
	array(
		'script'     => 'ImportDbRemote.php',
		'access'     => 'admin',
		'name'       => 'tools_em',
		'workspaces' => 'online',
	),
	1
);


// Use CLI script to do the import to reduce duplicate code
require_once t3lib_extMgm::extPath('contexts_wurfl')
	. 'Classes/Service/ImportCli.php';

/* @var $cli Tx_Contexts_Wurfl_Service_ImportCli */
$cli = t3lib_div::makeInstance('Tx_Contexts_Wurfl_Service_ImportCli');

// Set required arguments
$cli->cli_args = array(
	'_DEFAULT' => array(
		1 => 'import',
	),
	'--type' => array(
		'remote',
	),
	'--force-update' => array(
	),
);

echo '<pre>';
$cli->main();
echo '</pre>';
?>
