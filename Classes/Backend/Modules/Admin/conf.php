<?php

// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define(
    'TYPO3_MOD_PATH',
    '../typo3conf/ext/contexts_wurfl/Classes/Backend/Modules/Admin/'
);

$BACK_PATH = '../../../../../../../typo3/';

// Module configuration
$MCONF['name']   = 'tools_txcontextswurfl';
$MCONF['access'] = 'admin';
$MCONF['script'] = 'index.php';

$MLANG['default']['tabs_images']['tab']
    = 'moduleicon.gif';
$MLANG['default']['ll_ref']
    = 'LLL:EXT:contexts_wurfl/Classes/Backend/Modules/Admin/locallang_mod.xml';
?>
