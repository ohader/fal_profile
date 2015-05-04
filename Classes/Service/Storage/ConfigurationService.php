<?php
namespace OliverHader\FalProfile\Service\Storage;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Oliver Hader <oliver.hader@typo3.org>
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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use OliverHader\FalProfile\Domain\Object\Storage\Configuration;
use OliverHader\FalProfile\Bootstrap;

/**
 * @package fal_profile
 * @author Oliver Hader <oliver.hader@typo3.org>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConfigurationService implements SingletonInterface {

	/**
	 * @return ConfigurationService
	 */
	static public function create() {
		return GeneralUtility::makeInstance(__CLASS__);
	}

	/**
	 * @var array|Configuration[]
	 */
	protected $storageConfigurations = array();

	/**
	 * @param ResourceStorage $storage
	 * @return Configuration
	 */
	public function forStorage(ResourceStorage $storage) {
		if (!isset($this->storageConfigurations[$storage->getUid()])) {
			$configuration = Configuration::create();
			$storageRecord = $storage->getStorageRecord();

			if (!empty($storageRecord[Bootstrap::FIELD_Configuration])) {
				$configuration->__build($storageRecord[Bootstrap::FIELD_Configuration]);
			}

			$this->storageConfigurations[$storage->getUid()] = $configuration;
		}

		return $this->storageConfigurations[$storage->getUid()];
	}

}