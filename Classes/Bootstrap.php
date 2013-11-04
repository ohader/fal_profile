<?php
namespace OliverHader\FalProfile;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Oliver Hader <oliver.hader@typo3.org>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package fal_profile
 * @author Oliver Hader <oliver.hader@typo3.org>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Bootstrap {

	const FIELD_Configuration = 'fal_profile_configuration';
	const CONFIGURATION_Scope = 'FalProfile';

	/**
	 * Initializes this package.
	 */
	public static function initialize() {
		self::initializeSignals();
	}

	/**
	 * Initializes signals at the File Abstraction Layer
	 */
	protected static function initializeSignals() {
		self::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
			\TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PreFileProcess,
			'OliverHader\\FalProfile\\Slot\\FileProcessingSlot',
			'preProcess'
		);

		self::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
			\TYPO3\CMS\Core\Resource\ResourceStorage::SIGNAL_PostProcessConfiguration,
			'OliverHader\\FalProfile\\Slot\\ResourceStorageSlot',
			'postProcessConfiguration'
		);
	}

	/**
	 * Get the SignalSlot dispatcher
	 *
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	public static function getSignalSlotDispatcher() {
		$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
		return $signalSlotDispatcher;
	}

	/**
	 * Get the ObjectManager
	 *
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	public static function getObjectManager() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}

}
?>