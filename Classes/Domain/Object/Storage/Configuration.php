<?php
namespace OliverHader\FalProfile\Domain\Object\Storage;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * @package fal_profile
 * @author Oliver Hader <oliver.hader@typo3.org>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Configuration {

	/**
	 * @return Configuration
	 */
	static public function create() {
		return GeneralUtility::makeInstance(__CLASS__);
	}

	/**
	 * @var bool
	 */
	protected $enable;

	/**
	 * @var string
	 */
	protected $source;

	/**
	 * @var string
	 */
	protected $target;

	/**
	 * @param string $flexForm
	 */
	public function __build($flexForm) {
		$settings = ResourceFactory::getInstance()->convertFlexFormDataToConfigurationArray($flexForm);

		if (isset($settings['enable'])) {
			$this->setEnable($settings['enable']);
		}
		if (isset($settings['source'])) {
			$this->setSource($settings['source']);
		}
		if (isset($settings['target'])) {
			$this->setTarget($settings['target']);
		}
	}

	/**
	 * @return bool
	 */
	public function __validate() {
		return (
			!empty($this->enable)
			&& !empty($this->source)
			&& !empty($this->target)
		);
	}

	/**
	 * @return bool
	 */
	public function isEnabled() {
		return $this->getEnable();
	}

	/**
	 * @return bool
	 */
	public function getEnable() {
		return (bool)$this->enable;
	}

	/**
	 * @param bool $enable
	 */
	public function setEnable($enable) {
		$this->enable = (bool)$enable;
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param string $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @param string $target
	 */
	public function setTarget($target) {
		$this->target = $target;
	}

}