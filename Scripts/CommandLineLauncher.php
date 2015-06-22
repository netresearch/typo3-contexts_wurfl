<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts scheduler commandline script.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Scripts
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * WURFL contexts scheduler commandline script.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Scripts
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

defined('TYPO3_cliMode') or die('You cannot run this script directly!');

/**
 * Includes
 */
require_once t3lib_extMgm::extPath('contexts_wurfl')
	. 'Classes/Service/ImportCli.php';

/* @var $cli Tx_ContextsWurfl_Service_ImportCli */
$cli     = t3lib_div::makeInstance('Tx_ContextsWurfl_Service_ImportCli');
$nResult = $cli->main();

exit($nResult);
?>
