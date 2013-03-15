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
 */
class Tx_Contexts_Wurfl_Service_ImportTask extends tx_scheduler_Task
{
	/**
	 * Perform WURFL data import.
	 *
	 * @return boolean
	 */
	public function execute()
	{
		$instance = new Tx_Contexts_Wurfl_Api_Model_Import();
		return $instance->import();
	}
}
?>
