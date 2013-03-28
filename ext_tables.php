<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/FileStorage.php';
?>