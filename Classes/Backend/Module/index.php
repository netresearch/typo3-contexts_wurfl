<?php

// DEFAULT initialization of a module [BEGIN]
unset($MCONF);

require_once 'conf.php';
require_once $BACK_PATH . 'init.php';
require_once $BACK_PATH . 'template.php';

$LANG->includeLLFile('EXT:contexts_wurfl/Classes/Backend/Module/locallang.xml');

require_once PATH_t3lib . 'class.t3lib_scbase.php';

// This checks permissions and exits if the users has no permission for entry.
$BE_USER->modAccess($MCONF, 1);
// DEFAULT initialization of a module [END]


require_once 'class.tx_contextswurfl_module.php';

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_contextswurfl_module');
$SOBE->init();

// Include files?
foreach ($SOBE->include_once as $INC_FILE) {
	include_once $INC_FILE;
}

$SOBE->main();
$SOBE->printContent();
?>
