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
 * WURFL DatabaseConnector which uses the TYPO3 mysqli connection if available
 *
 * @category Contexts
 * @package  WURFL
 * @author   Christian Opitz <christian.opitz@netresearch.de>
 * @see      http://wurfl.sourceforge.net/help_doc.php
 */
class Tx_ContextsWurfl_Api_Wurfl_DatabaseConnector extends TeraWurflDatabase_MySQL5
{
    protected $sharedConnection = false;

    /**
     * Use the TYPO3 mysli connection when available (above 6.0.0)
     *
     * @global t3lib_db $TYPO3_DB
     * @return boolean
     */
    public function connect()
    {
        /* @var $TYPO3_DB t3lib_db */
        global $TYPO3_DB;
        if (method_exists($TYPO3_DB, 'getDatabaseHandle')) {
            $handle = $TYPO3_DB->getDatabaseHandle();
            if ($handle instanceof mysqli) {
                $this->dbcon = $handle;
                $this->connected = true;
                $this->sharedConnection = true;
                return true;
            }
        }
        return parent::connect();
    }

    /**
     * Let WURFL only deconnect, when the connection is not from TYPO3
     */
    public function __destruct()
    {
        if (!$this->sharedConnection) {
            parent::__destruct();
        }
    }
}
?>
