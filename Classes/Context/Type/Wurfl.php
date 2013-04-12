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
require_once t3lib_extMgm::extPath('contexts') . 'Classes/Context/Abstract.php';

/**
 * Matches on an WURFL parameter.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Context_Type_Wurfl extends Tx_Contexts_Context_Abstract
{
    /**
     * Match result (Default TRUE means matches any device).
     *
     * @var boolean
     */
    protected $match = true;

    /**
     * WURFL api instance.
     *
     * @var Tx_Contexts_Wurfl_Api_Wurfl
     */
    protected $wurfl = null;

    /**
     * This function gets called when the current contexts are determined.
     *
     * @param array $arDependencies Array of context objects that are
     *                              dependencies of this context
     *
     * @return boolean TRUE when your context matches, FALSE if not
     */
    public function match(array $arDependencies = array())
    {
        $this->match = true;
        $this->wurfl = new Tx_Contexts_Wurfl_Api_Wurfl();

        $this->matchDeviceType()
            ->matchDeviceDimension()
            ->matchProductInfo();

        return (bool) $this->match;
    }

    /**
     * Perform match by device type.
     *
     * @return Tx_Contexts_Wurfl_Context_Type_Wurfl
     */
    protected function matchDeviceType()
    {
        // Match mobile device
        if ($this->match
            && ((bool) $this->getConfValue('settings.isMobile', false))
        ) {
            $this->match &= $this->wurfl->isMobile();
        }

        // Match wireless device
        if ($this->match
            && ((bool) $this->getConfValue('settings.isWireless', false))
        ) {
            $this->match &= $this->wurfl->isWireless();
        }

        // Match tablet
        if ($this->match
            && ((bool) $this->getConfValue('settings.isTablet', false))
        ) {
            $this->match &= $this->wurfl->isTablet();
        }

        // Match smart tv
        if ($this->match
            && ((bool) $this->getConfValue('settings.isSmartTv', false))
        ) {
            $this->match &= $this->wurfl->isSmartTv();
        }

        // Match phone
        if ($this->match
            && ((bool) $this->getConfValue('settings.isPhone', false))
        ) {
            $this->match &= $this->wurfl->isPhone();
        }

        return $this;
    }

    /**
     * Perform match by device dimension.
     *
     * @return Tx_Contexts_Wurfl_Context_Type_Wurfl
     */
    protected function matchDeviceDimension()
    {
        $screenWidthMin = (int) $this->getConfValue(
            'settings.screenWidthMin', null, 'sDimension'
        );
        $screenWidthMax = (int) $this->getConfValue(
            'settings.screenWidthMax', null, 'sDimension'
        );

        $screenHeightMin = (int) $this->getConfValue(
            'settings.screenHeightMin', null, 'sDimension'
        );
        $screenHeightMax = (int) $this->getConfValue(
            'settings.screenHeightMax', null, 'sDimension'
        );

        if ($this->match) {
            $width = $this->wurfl->getScreenWidth();
            if ($screenWidthMin > 0) {
                $this->match &= $width >= $screenWidthMin;
            }
            if ($screenWidthMax > 0) {
                $this->match &= $width <= $screenWidthMax;
            }
        }

        if ($this->match) {
            $height = $this->wurfl->getScreenHeight();
            if ($screenHeightMin > 0) {
                $this->match &= $height >= $screenHeightMin;
            }
            if ($screenHeightMax > 0) {
                $this->match &= $height <= $screenHeightMax;
            }
        }

        return $this;
    }

    /**
     * Perform match by product infos.
     *
     * @return Tx_Contexts_Wurfl_Context_Type_Wurfl
     */
    protected function matchProductInfo()
    {
        // Brand names
        $brandNames = $this->getConfValue(
            'settings.brandName', null, 'sProductInfo'
        );

        if (strlen($brandNames)) {
            $brandNames = explode(',', $brandNames);
            $brandMatch = false;

            foreach ($brandNames as $brandName) {
                $brandMatch |= (
                    strcasecmp($brandName, $this->wurfl->getBrandName()) === 0
                );
            }

            $this->match &= $brandMatch;
        }

        // Model names
        $modelNames = $this->getConfValue(
            'settings.modelName', null, 'sProductInfo'
        );

        if (strlen($modelNames)) {
            $modelNames = explode(',', $modelNames);
            $modelMatch = false;

            foreach ($modelNames as $modelName) {
                $modelMatch |= (
                    strcasecmp($modelName, $this->wurfl->getModelName()) === 0
                );
            }

            $this->match &= $modelMatch;
        }

        // Mobile browsers
        $mobileBrowsers = $this->getConfValue(
            'settings.mobileBrowser', null, 'sProductInfo'
        );

        if (strlen($mobileBrowsers)) {
            $mobileBrowsers = explode(',', $mobileBrowsers);
            $mobileMatch    = false;

            foreach ($mobileBrowsers as $mobileBrowser) {
                $mobileMatch |= (
                    strcasecmp($mobileBrowser, $this->wurfl->getMobileBrowser()) === 0
                );
            }

            $this->match &= $mobileMatch;
        }

        return $this;
    }
}
?>
