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
	 * This function gets called when the current contexts are determined.
	 *
	 * @param array $arDependencies Array of context objects that are
	 *                              dependencies of this context
	 *
	 * @return boolean TRUE when your context matches, FALSE if not
	 */
	public function match(array $arDependencies)
	{
		// TODO
	}
}
?>
