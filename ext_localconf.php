<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE != 'BE') {
	//we load that file in ext_tables.php for the backend
	require_once t3lib_extMgm::extPath($_EXTKEY) . 'ext_contexts.php';
}
?>
