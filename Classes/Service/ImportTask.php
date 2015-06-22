<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts scheduler import task.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * This class provides a task to import the WURFL data.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_ContextsWurfl_Service_ImportTask extends tx_scheduler_Task
{
    /**
     * Perform WURFL data import.
     *
     * @return boolean
     */
    public function execute()
    {
        $importer = new Tx_ContextsWurfl_Api_Model_Import(
            TeraWurflUpdater::SOURCE_REMOTE
        );

        $result = $importer->import();

        // No update available, WURFL data is already up to date
        if ($result === Tx_ContextsWurfl_Api_Model_Import::STATUS_NO_UPDATE) {
            $this->scheduler->log(
                'No update necessary. Your WURFL data is already up to date.'
            );
            return true;
        }

        $this->showStatus($result, $importer->getUpdater());
        return true;
    }

    /**
     * Show status of update.
     *
     * @param boolean          $status  TRUE/FALSE
     * @param TeraWurflUpdater $updater WURFL updater instance
     *
     * @return void
     */
    protected function showStatus($status, TeraWurflUpdater $updater)
    {
        if ($status) {
            $this->scheduler->log('Database Update OK');

            $this->scheduler->log(
                'WURFL Version: '
                . $updater->loader->version
                . ' (' . $updater->loader->last_updated . ')'
            );
            $this->scheduler->log(
                'WURFL Devices: '
                . $updater->loader->mainDevices
            );
            $this->scheduler->log(
                'PATCH New Devices: '
                . $updater->loader->patchAddedDevices
            );
            $this->scheduler->log(
                'PATCH Merged Devices: '
                . $updater->loader->patchMergedDevices
            );

            if (count($updater->loader->errors) > 0) {
                $this->scheduler->log('Errors:');

                foreach ($updater->loader->errors as $error) {
                    $this->scheduler->log($error);
                }
            }
        } else {
            $this->scheduler->log('ERROR LOADING DATA!');
            $this->scheduler->log('Errors:');

            foreach ($updater->loader->errors as $error) {
                $this->scheduler->log($error);
            }
        }
    }
}
?>
