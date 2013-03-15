<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts import module.
 *
 * PHP version 5
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * Class used to import WURFL data.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Api_Model_Import
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
	}

	public function import()
	{
var_dump($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['contexts_wurfl']);
exit;

		$this->download(
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['contexts_wurfl']['importUrl']
		);

		return true;
	}

	/**
	 * Downloads the configured WURFL xml file.
	 *
	 * @return string
	 */
	protected function download($importUrl)
	{
var_dump($importUrl);
exit;
	}
}
?>
