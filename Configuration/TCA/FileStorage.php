<?php
$tempColumns = array (
	'fal_profile_configuration' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:fal_profile/Resources/Private/Language/locallang_db.xml:sys_file_storage.FalProfile.configuration',
		'config' => array(
			'type' => 'flex',
			'ds' => array(
				'default' => 'FILE:EXT:fal_profile/Configuration/Flexform/fal_profile_configuration.xml',
			),
		)
	),
);

\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('sys_file_storage');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_storage', $tempColumns, TRUE);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'sys_file_storage',
	'--div--;LLL:EXT:fal_profile/Resources/Private/Language/locallang_db.xml:sys_file_storage.FalProfile.div, fal_profile_configuration;;;;1-1-1'
);
?>