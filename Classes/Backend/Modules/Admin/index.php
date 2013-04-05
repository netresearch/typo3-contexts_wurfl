<?php

// DEFAULT initialization of a module [BEGIN]
unset($MCONF);

require_once 'conf.php';
require_once $BACK_PATH . 'init.php';
require_once $BACK_PATH . 'template.php';

$LANG->includeLLFile(
    'EXT:contexts_wurfl/Resources/Private/Language/locallang_mod.xml'
);

require_once PATH_t3lib . 'class.t3lib_scbase.php';

// This checks permissions and exits if the users has no permission for entry.
$BE_USER->modAccess($MCONF, 1);
// DEFAULT initialization of a module [END]


require_once 'Module.php';

/* @var $module Tx_Contexts_Wurfl_Backend_Modules_Admin_Module */
$module = t3lib_div::makeInstance('Tx_Contexts_Wurfl_Backend_Modules_Admin_Module');
$module->init();

// Include files?
foreach ($module->include_once as $includeFile) {
    require_once $includeFile;
}

$module->main();
$module->printContent();
?>
