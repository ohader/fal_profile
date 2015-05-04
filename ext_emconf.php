<?php
$EM_CONF[$_EXTKEY] = array(
	'title' => 'Profile processing for File Abstraction Layer',
	'description' => 'ICC Profile processing for accordant storage of the File Abstraction Layer',
	'category' => 'misc',
	'author' => 'Oliver Hader',
	'author_email' => 'oliver.hader@typo3.org',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '',
	'createDirs' => 'uploads/fal_profile',
	'modify_tables' => 'sys_file_storage',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.3.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
