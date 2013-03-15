<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	// All other modes did load it already
	require_once t3lib_extMgm::extPath($_EXTKEY) . 'ext_contexts.php';
}

?>