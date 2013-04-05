<?php
declare(encoding = 'UTF-8');

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Netresearch GmbH & Co. KG <typo3.org@netresearch.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Includes
 */
$strApiPath = realpath(
    PATH_typo3conf . 'ext/contexts_wurfl/Library/wurfl-dbapi-1.4.4.0/'
);

require_once $strApiPath . '/TeraWurfl.php';

/**
 * Backend helper class.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Backend
{
    /**
     * Get all brand names.
     *
     * @param array          &$params      Additional parameters
     * @param t3lib_TCEforms $parentObject Parent object instance
     *
     * @return void
     */
    public function getBrandNames(array &$params, t3lib_TCEforms $parentObject)
    {
        $wurfl      = new TeraWurfl();
        $brandNames = array();

        foreach ($wurfl->db->getMatcherTableList() as $tableName) {
            $brandNames[] = str_replace(
                TeraWurflConfig::$TABLE_PREFIX . '_', '', $tableName
            );
        }

        natcasesort($brandNames);

        foreach ($brandNames as $brandName) {
            $params['items'][] = array(
                $brandName,
                $brandName
            );
        }
    }

    /**
     * Get all model names belonging to a selected brand.
     *
     * @param array          &$params      Additional parameters
     * @param t3lib_TCEforms $parentObject Parent object instance
     *
     * @return void
     */
    public function getModelNames(array &$params, t3lib_TCEforms $parentObject)
    {
        $flexFormConfig = t3lib_div::xml2array($params['row']['type_conf']);
        $brandName      = '';

        if (is_array($flexFormConfig)
            && !empty($flexFormConfig['data']['sProductInfo']['lDEF']['settings.brandName']['vDEF'])
        ) {
            $brandName = $flexFormConfig['data']['sProductInfo']
                ['lDEF']['settings.brandName']['vDEF'];
        }

        /* @var $TYPO3_DB t3lib_db */
        global $TYPO3_DB;

        $devices = $TYPO3_DB->exec_SELECTgetRows(
            'capabilities',
            TeraWurflConfig::$TABLE_PREFIX . '_' . $brandName,
            '1 = 1'
        );

        $modelNames = array();

        foreach ($devices as $device) {
            $capabilities = @unserialize($device['capabilities']);

            // Add model name if it exists and not already in the list
            if (($capabilities !== false)
                && isset($capabilities['product_info'])
                && isset($capabilities['product_info']['model_name'])
                && strlen($capabilities['product_info']['model_name'])
                && !in_array(
                    $capabilities['product_info']['model_name'],
                    $modelNames
                )
            ) {
                $modelNames[] = $capabilities['product_info']['model_name'];
            }
        }

        natcasesort($modelNames);

        foreach ($modelNames as $modelName) {
            $params['items'][] = array(
                $modelName,
                $modelName
            );
        }
    }

    /**
     * Get all mobile browsers.
     *
     * @param array          &$params      Additional parameters
     * @param t3lib_TCEforms $parentObject Parent object instance
     *
     * @return void
     */
    public function getMobileBrowsers(array &$params, t3lib_TCEforms $parentObject)
    {
        /* @var $TYPO3_DB t3lib_db */
        global $TYPO3_DB;

        $wurfl          = new TeraWurfl();
        $mobileBrowsers = array();

        // Go through all brands and all devices to get all mobile browsers
        foreach ($wurfl->db->getMatcherTableList() as $tableName) {
            $devices = $TYPO3_DB->exec_SELECTgetRows(
                'capabilities',
                $tableName,
                '1 = 1'
            );

            foreach ($devices as $device) {
                $capabilities = @unserialize($device['capabilities']);

                // Add mobile browser name if it exists and not already in the list
                if (($capabilities !== false)
                    && isset($capabilities['product_info'])
                    && isset($capabilities['product_info']['mobile_browser'])
                    && strlen($capabilities['product_info']['mobile_browser'])
                    && !in_array(
                        $capabilities['product_info']['mobile_browser'],
                        $mobileBrowsers
                    )
                ) {
                    $mobileBrowsers[] = $capabilities['product_info']['mobile_browser'];
                }
            }
        }

        natcasesort($mobileBrowsers);

        foreach ($mobileBrowsers as $mobileBrowser) {
            $params['items'][] = array(
                $mobileBrowser,
                $mobileBrowser
            );
        }
    }
}
?>
