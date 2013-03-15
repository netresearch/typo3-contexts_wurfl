<?php
/**
 * Contexts WURFL context registration configuration.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

defined('TYPO3_MODE') or die('Access denied.');

// Register our context
Tx_Contexts_Api_Configuration::registerContextType(
	'wurfl',
	'Geräteeigenschaften',
	'Tx_Contexts_Wurfl_Context_Type_Wurfl',
	'FILE:EXT:contexts_wurfl/Configuration/flexform/Wurfl.xml'
);

?>
