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
 * Includes
 */
$strApiPath = realpath(
    PATH_typo3conf . 'ext/contexts_wurfl/Library/wurfl-dbapi-1.4.4.0/'
);

require_once $strApiPath . '/TeraWurfl.php';
require_once $strApiPath . '/TeraWurflUtils/TeraWurflUpdater.php';

/**
 * Class used to import WURFL data.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_ContextsWurfl_Api_Model_Import
{
    /**
     * @var Return code: No update available, Data up to date
     */
    const STATUS_NO_UPDATE = 1;

    /**
     * @var Extension has not been configured yet
     */
    const STATUS_NOT_CONFIGURED = 254;

    /**
     * @var Some error occured
     */
    const STATUS_ERROR = 255;

    /**
     * TeraWurfl instance.
     *
     * @var TeraWurfl
     */
    protected $wurfl = null;

    /**
     * TeraWurflUpdater instance.
     *
     * @var TeraWurflUpdater
     */
    protected $updater = null;

    /**
     * Type of source to import from.
     *
     * @var string
     */
    protected $type = TeraWurflUpdater::SOURCE_LOCAL;

    /**
     * Constructor.
     *
     * @param string $type Type of source to import from
     */
    public function __construct($type = TeraWurflUpdater::SOURCE_LOCAL)
    {
        if (!is_dir(TeraWurflConfig::$DATADIR)) {
            mkdir(TeraWurflConfig::$DATADIR, 0777, true);
        }

        $this->type    = $type;
        $this->wurfl   = new TeraWurfl();
        $this->updater = new TeraWurflUpdater($this->wurfl, $type);
    }

    /**
     * Get updater instance.
     *
     * @return TeraWurflUpdater
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Imports the WURFL resource file from the specified source
     * (local or remote).
     *
     * @param boolean $force Force update (only valid for type "remote")
     *
     * @return integer Import status code, see class constants
     */
    public function import($force = false)
    {
        if ($this->type === TeraWurflUpdater::SOURCE_REMOTE) {
            // Use download url from extension configuration
            $extConf = unserialize(
                $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_wurfl']
            );

            if (empty($extConf['remoteRepository'])) {
                return self::STATUS_NOT_CONFIGURED;
            }

            $this->updater->downloader->download_url
                = $extConf['remoteRepository'];

            try {
                $available = $this->updater->isUpdateAvailable();
            } catch (Exception $ex) {
                $available = true;
            }

            // No update available, WURFL data is already up to date
            if (!$force && !$available) {
                return self::STATUS_NO_UPDATE;
            }
        }

        try {
            // update() returns TRUE or FALSE
            return $this->updater->update();
        } catch (TeraWurflUpdateDownloaderException $ex) {
            return self::STATUS_ERROR;
        }

        return true;
    }
}
?>
