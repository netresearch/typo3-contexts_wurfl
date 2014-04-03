<?php

// DEFAULT initialization of a module [BEGIN]
unset($MCONF);

require_once 'conf.php';
require_once $BACK_PATH . 'init.php';

if (t3lib_utility_VersionNumber::convertVersionNumberToInteger($GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version']) < 6002000) {
    include_once $BACK_PATH . 'template.php';
    include_once PATH_t3lib . 'class.t3lib_scbase.php';
}

$LANG->includeLLFile(
    'EXT:contexts_wurfl/Resources/Private/Language/locallang_mod.xml'
);

// This checks permissions and exits if the users has no permission for entry.
$BE_USER->modAccess($MCONF, 1);
// DEFAULT initialization of a module [END]


require_once 'Module.php';

/* @var $module Tx_Contexts_Wurfl_Backend_Modules_Admin_Module */
$module = t3lib_div::makeInstance('Tx_Contexts_Wurfl_Backend_Modules_Admin_Module');
$module->init();

// Include files?
foreach ($module->include_once as $includeFile) {
    include_once $includeFile;
}

$module->main();
$module->printContent();
?>
