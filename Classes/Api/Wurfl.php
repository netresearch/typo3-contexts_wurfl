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
 * WURFL api integration. Provides some useful methods to
 * access common capabilities.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 * @see      http://wurfl.sourceforge.net/help_doc.php
 */
class Tx_Contexts_Wurfl_Api_Wurfl extends TeraWurfl
{
	/**
	 * Constructor.
	 *
	 * @param string $userAgent  HTTP user agent string
	 * @param string $httpAccept HTTP accept header
	 */
	public function __construct($userAgent = null, $httpAccept = null)
	{
		parent::__construct();

		// Get device capabilities
		$this->getDeviceCapabilitiesFromAgent($userAgent, $httpAccept);
	}

	/**
	 * Returns TRUE if the device is a wireless one.
	 *
	 * @return boolean
	 */
	public function isWireless()
	{
		return $this->getDeviceCapability('is_wireless_device');
	}

	/**
	 * Returns TRUE if the device is a smart tv.
	 *
	 * @return boolean
	 */
	public function isSmartTv()
	{
		return $this->getDeviceCapability('is_smarttv');
	}

	/**
	 * Returns TRUE if the device is a tablet.
	 *
	 * @return boolean
	 */
	public function isTablet()
	{
		return $this->getDeviceCapability('is_tablet');
	}

	/**
	 * Returns TRUE if the device is a phone.
	 *
	 * @return boolean
	 */
	public function isPhone()
	{
		return $this->getDeviceCapability('can_assign_phone_number');
	}

	/**
	 * Returns TRUE if the device is a mobile device.
	 *
	 * @return boolean
	 */
	public function isMobile()
	{
		return ($this->isWireLess() || $this->isTablet());
	}

	/**
	 * Get the screen width in pixel.
	 *
	 * @return integer
	 */
	public function getScreenWidth()
	{
		return $this->getDeviceCapability('resolution_width');
	}

	/**
	 * Get the screen height in pixel.
	 *
	 * @return integer
	 */
	public function getScreenHeight()
	{
		return $this->getDeviceCapability('resolution_height');
	}
}
?>
