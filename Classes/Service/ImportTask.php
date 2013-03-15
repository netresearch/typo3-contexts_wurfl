<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts scheduler import task.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * This class provides a task to import the WURFL data.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 */
class Tx_Contexts_Wurfl_Service_ImportTask extends tx_scheduler_Task
{
	/**
	 * ???
	 *
	 * @return boolean
	 */
	public function execute()
	{
		return true;
	}
}

$xclass = $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS'];
$xfile  = 'ext/contexts_wurfl/Classes/Service/ImportTask.php';

if (defined('TYPO3_MODE') && isset($xclass[$xfile])) {
	include_once($xclass[$xfile]);
}
?>
