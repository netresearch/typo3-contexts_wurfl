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
        $screenWidth = (int) $this->getConfValue(
            'settings.screenWidth', null, 'sDimension'
        );

        $screenHeight = (int) $this->getConfValue(
            'settings.screenHeight', null, 'sDimension'
        );

        if ($this->match && ($screenWidth > 0)) {
            $this->match &= ($this->wurfl->getScreenWidth() <= $screenWidth);
        }

        if ($this->match && ($screenHeight > 0)) {
            $this->match &= ($this->wurfl->getScreenHeight() <= $screenHeight);
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
        // Brand name
        $brandName = $this->getConfValue(
            'settings.brandName', null, 'sProductInfo'
        );

        if (strlen($brandName)) {
            $this->match &= (
                strcasecmp($brandName, $this->wurfl->getBrandName()) === 0
            );
        }

        // Model name
        $modelName = $this->getConfValue(
            'settings.modelName', null, 'sProductInfo'
        );

        if (strlen($modelName)) {
            $this->match &= (
                strcasecmp($modelName, $this->wurfl->getModelName()) === 0
            );
        }

        // Mobile browser
        $mobileBrowser = $this->getConfValue(
            'settings.mobileBrowser', null, 'sProductInfo'
        );

        if (strlen($mobileBrowser)) {
            $this->match &= (
                strcasecmp($mobileBrowser, $this->wurfl->getMobileBrowser()) === 0
            );
        }

        return $this;
    }
}
?>
