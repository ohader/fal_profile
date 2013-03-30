<?php
namespace OliverHader\FalProfile\Slot;
use OliverHader\FalProfile\Bootstrap;

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
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
class ResourceStorageSlot implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Post-processes the configuration.
	 *
	 * @param \TYPO3\CMS\Core\Resource\ResourceStorage $storage
	 */
	public function postProcessConfiguration(\TYPO3\CMS\Core\Resource\ResourceStorage $storage) {
		$storageRecord = $storage->getStorageRecord();

		if (!empty($storageRecord[Bootstrap::FIELD_Configuration])) {
			$scopeConfiguration = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->convertFlexFormDataToConfigurationArray(
				$storageRecord[Bootstrap::FIELD_Configuration]
			);

			if (!empty($scopeConfiguration)) {
				$configuration = $storage->getConfiguration();
				$configuration[Bootstrap::CONFIGURATION_Scope] = $scopeConfiguration;
				$storage->setConfiguration($configuration);
			}
		}
	}

}
?>