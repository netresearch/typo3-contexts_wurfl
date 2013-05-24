<?php
declare(encoding = 'UTF-8');

/**
 * Extension module integration.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Module
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * Includes
 */
$strApiPath = realpath(
    PATH_typo3conf . 'ext/contexts_wurfl/Library/wurfl-dbapi-1.4.4.0/'
);

require_once $strApiPath . '/TeraWurfl.php';

/**
 * Module of the contexts_wurfl extension.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Module
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Backend_Modules_Admin_Module extends t3lib_SCbase
{
    /**
     * Adds items to the ->MOD_MENU array. Used for the function menu selector.
     *
     * @return void
     */
    public function menuConfig()
    {
        /* @var $LANG language */
        global $LANG;

        $this->MOD_MENU = array(
            'function' => array(
                '1' => $LANG->getLL('module.admin.function1'),
                '2' => $LANG->getLL('module.admin.function2'),
                '3' => $LANG->getLL('module.admin.function3'),
                '4' => $LANG->getLL('module.admin.function4'),
            )
        );

        parent::menuConfig();
    }

    /**
     * Main function of the module. Write the content to $this->content
     * If you chose "web" as main module, you will need to consider the
     * $this->id parameter which will contain the uid-number of the page
     * clicked in the page tree.
     *
     * @return void
     */
    public function main()
    {
        /* @var $LANG language */
        global $LANG;

        /* @var $BE_USER t3lib_beUserAuth */
        global $BE_USER;

        global $BACK_PATH;

        $adminTitle = $LANG->getLL('module.admin.title');

        // Access check!
        // The page will show only if there is a valid page and if
        // this page may be viewed by the user
        $pageinfo
            = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);

        if (($this->id && is_array($pageinfo))
            || ($BE_USER->user['admin'] && !$this->id)
        ) {
            // Draw the header.
            $this->doc = t3lib_div::makeInstance('bigDoc');

            $this->doc->backPath = $BACK_PATH;
            $this->doc->form     = '<form action="" method="POST">';

            // JavaScript
            $this->doc->JScode = <<<JS
<script type="text/javascript">
//<![CDATA[
    script_ended = 0;
    function jumpToUrl(URL)    {
        document.location = URL;
    }
//]]>
</script>
JS;
            $this->doc->postCode = <<<JS
<script type="text/javascript">
//<![CDATA[
    script_ended = 1;
    if (top.fsMod) {
        top.fsMod.recentIds["web"] = 0;
    }
//]]>
</script>
JS;

            $headerSection = $this->doc->getHeader(
                'pages', $pageinfo, $pageinfo['_thePath']
            )
            . '<br />'
            . $LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.path')
            . ': '
            . t3lib_div::fixed_lgd_cs($pageinfo['_thePath'], -50);

            $this->content .= $this->doc->startPage($adminTitle);
            $this->content .= $this->doc->header($adminTitle);
            $this->content .= $this->doc->spacer(5);
            $this->content .= $this->doc->section(
                '',
                $this->doc->funcMenu(
                    $headerSection,
                    t3lib_BEfunc::getFuncMenu(
                        $this->id,
                        'SET[function]',
                        $this->MOD_SETTINGS['function'],
                        $this->MOD_MENU['function']
                    )
                )
            );
            $this->content .= $this->doc->divider(5);

            // Render content
            $this->moduleContent();

            // ShortCut
            if ($BE_USER->mayMakeShortcut()) {
                $this->content .= $this->doc->spacer(20)
                    . $this->doc->section(
                        '',
                        $this->doc->makeShortcutIcon(
                            'id',
                            implode(',', array_keys($this->MOD_MENU)),
                            $this->MCONF['name']
                        )
                    );
            }

            $this->content .= $this->doc->spacer(10);
        } else {
            // If no access or if ID == zero
            $this->doc = t3lib_div::makeInstance('bigDoc');
            $this->doc->backPath = $BACK_PATH;

            $this->content .= $this->doc->startPage($adminTitle);
            $this->content .= $this->doc->header($adminTitle);
            $this->content .= $this->doc->spacer(5);
            $this->content .= $this->doc->spacer(10);
        }
    }

    /**
     * Prints out the module HTML
     *
     * @return void
     */
    public function printContent()
    {
        $this->content .= $this->doc->endPage();
        echo $this->content;
    }

    /**
     * Generates the module content.
     *
     * @return void
     */
    public function moduleContent()
    {
        if (!$this->handleDataDir()) {
            return;
        }

        if (!$this->checkDatabase()) {
            return;
        }

        switch ((string) $this->MOD_SETTINGS['function']) {
        case 1:
            $this->handleQueryDatabase();
            break;

        case 2:
            $this->handleUpdateDatabase();
            break;

        case 3:
            $this->handleClearCache();
            break;

        case 4:
            $this->handleRebuildCache();
            break;
        }
    }

    /**
     * Get module head content.
     *
     * @return string
     */
    protected function getContentHead()
    {
        return <<<HTML
<style type="text/css">
    .wurfl-module {
        margin: 10px 0px 20px;
    }
    .wurfl-module ul {
        list-style: circle;
        padding-left: 16px;
    }
    .wurfl-module ul li {
        margin: 0px;
    }
    .wurfl-module ul li a {
        text-decoration: underline;
    }
    .wurfl-module ul li a:hover {
        text-decoration: none;
    }
</style>
HTML;
    }

    /**
     * Methods handles the checking and creation of the data directory.
     * Returns TRUE if the temporary directory exists, FALSE otherwise.
     *
     * @return boolean
     */
    protected function handleDataDir()
    {
        $dataPath = PATH_site . 'typo3temp/contexts_wurfl/';

        if (!file_exists($dataPath)) {
            $content = $this->getContentHead();

            $content .= <<<HTML
<div class="wurfl-module">
    <div style="margin-bottom: 20px;">
        Create required data directory "$dataPath".
    </div>
    <input type="submit" name="cmd[createDATADIR]" value="Create data directory" />
</div>
HTML;

            if ($_POST['cmd']['createDATADIR']) {
                if (!mkdir($dataPath)) {
                    $content .= <<<HTML
<div style="margin: 20px 0px;" class="typo3-message message-error">
    <strong>ERROR: </strong>Unable to create directory "$dataPath".
</div>
HTML;
                } else {
                    $content .= <<<HTML
<div style="margin: 20px 0px;" class="typo3-message message-ok">
    Successfully created new directory "$dataPath".
</div>
HTML;
                }
            }

            $this->content .= $this->doc->section(
                'Create WURFL data directory:', $content, false, true
            );

            return false;
        }

        return true;
    }

    /**
     * Methods checks the database and performs an initial setup of it.
     *
     * @return boolean
     */
    protected function checkDatabase()
    {
        try {
            $wurfl = new TeraWurfl();
            $wurfl->getDeviceCapabilitiesFromAgent(null, null);

            return true;
        } catch (Exception $e) {
            $content = $this->getContentHead();

            $content .= <<<HTML
<div style="margin: 20px 0px;" class="typo3-message message-error">
    <strong>ERROR: </strong>No valid WURFL database found. An initial import has do be done.
</div>
HTML;

            // Use download url from extension configuration
            $extConf = unserialize(
                $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_wurfl']
            );

            $downloadUrl = $extConf['remoteRepository'];

            $content .= <<<HTML
<div class="wurfl-module">
    <div>Location: $downloadUrl</div>
    <div><em>The download url can be configured in the "contexts_wurfl" extension configuration.</em></div>
    <div style="margin: 20px 0px;" class="typo3-message message-ok">
        <strong>INFO: </strong>Be patient the initial import may take some time.
    </div>
    <input type="submit" name="cmd[updateDatabase]" value="Perform initial import" />
</div>
HTML;

            $content .= $this->performDatabaseUpdate();

            $this->content .= $this->doc->section(
                'Initial WURFL setup:', $content, false, true
            );
        }

        return false;
    }

    /**
     * Methods checks the post command and starts an database update.
     *
     * @return string
     */
    protected function performDatabaseUpdate()
    {
        if ($_POST['cmd']['updateDatabase']) {
            $importer = new Tx_Contexts_Wurfl_Api_Model_Import(
                TeraWurflUpdater::SOURCE_REMOTE
            );

            $updateResult = $this->showStatus(
                $importer->import(true),
                $importer->getUpdater()
            );

            return <<<HTML
<div class="wurfl-module">
    $updateResult
</div>
HTML;
        }

        return '';
    }

    /**
     * Methods handles the database query to test the WURFL library.
     *
     * @return void
     */
    protected function handleQueryDatabase()
    {
        $content  = $this->getContentHead();
        $content .= <<<HTML
<div class="wurfl-module">
    <h4>Input a USER-AGENT string, e.g.</h4>
    <ul>
        <li>Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46</li>
        <li>BlackBerry_4</li>
        <li>NokiaC2-05.1/2.0 (08.45) Profile/MIDP-2.1 Configuration/CLDC-1.1</li>
    </ul>

    <input type="text" name="inputCalc[agent][input]" size="80" />
    <br /><br/>
    <input type="submit" name="cmd[queryDatabase]" value="Submit" />
</div>
HTML;
        if ($_POST['cmd']['queryDatabase']
            && trim($_POST['inputCalc']['agent']['input'])
        ) {
            $wurfl = new Tx_Contexts_Wurfl_Api_Wurfl(
                $_POST['inputCalc']['agent']['input']
            );

            $brandName = $wurfl->capabilities['product_info']['brand_name'];
            $modelName = $wurfl->capabilities['product_info']['model_name'];

            $arrayOutput = t3lib_utility_Debug::viewArray(
                $wurfl->capabilities
            );

            $content .= <<<HTML
<div class="wurfl-module">
    <h3>Details for the <em>$brandName $modelName</em></h3>
    <div class="wurfl-module">$arrayOutput</div>
</div>
HTML;
        }

        $this->content .= $this->doc->section(
            'Query WURFL for user agent capabilities',
            $content, false, true
        );
    }

    /**
     * Methods handles the update of the WURFL database.
     *
     * @return void
     */
    protected function handleUpdateDatabase()
    {
        // Use download url from extension configuration
        $extConf = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_wurfl']
        );

        $downloadUrl = $extConf['remoteRepository'];

        $content  = $this->getContentHead();
        $content .= <<<HTML
<div class="wurfl-module">
    <div>Location: $downloadUrl</div>
    <div><em>The download url can be configured in the "contexts_wurfl" extension configuration.</em></div>
    <div style="margin: 20px 0px;" class="typo3-message message-warning">
        <strong>WARNING: </strong>This will replace your existing WURFL database.
    </div>
    <input type="submit" name="cmd[updateDatabase]" value="Update database" />
</div>
HTML;

        $content .= $this->performDatabaseUpdate();

        $this->content .= $this->doc->section(
            'Update WURFL database from a remote repository',
            $content, false, true
        );
    }

    /**
     * Methods handles the clearing of the database table cache.
     *
     * @return void
     */
    protected function handleClearCache()
    {
        $content  = $this->getContentHead();
        $content .= <<<HTML
<div class="wurfl-module">
    <div style="margin-bottom: 20px;">
        Clears the cache table.
    </div>
    <input type="submit" name="cmd[clearCache]" value="Clear cache" />
</div>
HTML;

        if ($_POST['cmd']['clearCache']) {
            $wurfl  = new TeraWurfl();
            $result = $wurfl->db->createCacheTable();

            if ($result) {
                $content .= <<<HTML
<div class="wurfl-module">
    <div style="margin: 35px 0px 0px;" class="typo3-message message-ok">
        The cache has been successfully cleared.
    </div>
</div>
HTML;
            }
        }

        $this->content .= $this->doc->section(
            'Clear WURFL cache', $content, false, true
        );
    }

    /**
     * Methods handles the rebuilding of the database table cache.
     *
     * @return void
     */
    protected function handleRebuildCache()
    {
        $content  = $this->getContentHead();
        $content .= <<<HTML
<div class="wurfl-module">
    <div style="margin-bottom: 20px;">
        Rebuilds the cache table by redetecting the cached devices against the loaded WURFL.
    </div>
    <input type="submit" name="cmd[rebuildCache]" value="Rebuild cache" />
</div>
HTML;

        if ($_POST['cmd']['rebuildCache']) {
            $wurfl  = new TeraWurfl();
            $result = $wurfl->db->rebuildCacheTable();

            if ($result) {
                $content .= <<<HTML
<div class="wurfl-module">
    <div style="margin: 35px 0px 0px;" class="typo3-message message-ok">
        The cache has been successfully rebuilt.
    </div>
</div>
HTML;
            }
        }

        $this->content .= $this->doc->section(
            'Rebuild WURFL cache', $content, false, true
        );
    }

    /**
     * Get status HTML of update.
     *
     * @param boolean          $status  TRUE/FALSE
     * @param TeraWurflUpdater $updater WURFL updater instance
     *
     * @return string
     */
    protected function showStatus($status, TeraWurflUpdater $updater)
    {
        $message = '';

        if ($status) {
            $version            = $updater->loader->version;
            $lastUpdated        = $updater->loader->last_updated;
            $mainDevices        = $updater->loader->mainDevices;
            $patchAddedDevices  = $updater->loader->patchAddedDevices;
            $patchMergedDevices = $updater->loader->patchMergedDevices;

            $message .= <<<HTML
<div style="margin: 35px 0px 0px;" class="typo3-message message-ok">
    Update of database successfully done.
    <ul>
        <li>WURFL Version: $version ($lastUpdated)</li>
        <li>WURFL Devices: $mainDevices</li>
        <li>PATCH New Devices: $patchAddedDevices</li>
        <li>PATCH Merged Devices: $patchMergedDevices</li>
    </ul>
</div>
HTML;

            if (count($updater->loader->errors) > 0) {
                $message .= <<<HTML
<div style="margin: 10px 0px 0px;" class="typo3-message message-error">
    The following errors occured during update:
    <ul>
HTML;
                foreach ($updater->loader->errors as $error) {
                    $message .= "<li>$error</li>";
                }

                $message .= '</ul></div>';
            }
        } else {
            $message .= <<<HTML
<div style="margin: 35px 0px 0px;" class="typo3-message message-error">
    Update of database failed. The following errors occured:
    <ul>
HTML;
            foreach ($updater->loader->errors as $error) {
                $message .= "<li>$error</li>";
            }

            $message .= '</ul></div>';

        }

        return $message;
    }
}
?>
