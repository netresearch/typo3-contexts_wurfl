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

// Register context
Tx_Contexts_Api_Configuration::registerContextType(
    'wurfl',
    'LLL:EXT:contexts_wurfl/Resources/Private/Language/locallang_mod.xml:context.title',
    'Tx_Contexts_Wurfl_Context_Type_Wurfl',
    'FILE:EXT:contexts_wurfl/Configuration/flexform/Wurfl.xml'
);

?>
