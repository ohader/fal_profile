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
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FileProcessingSlot implements \TYPO3\CMS\Core\SingletonInterface {

	const DEFAULT_ProcessingFolder = '_processed_profile_';

	/**
	 * @var \TYPO3\CMS\Core\Imaging\GraphicalFunctions
	 */
	protected $graphicalFunctions;

	/**
	 * @var array|\TYPO3\CMS\Core\Resource\Folder[]
	 */
	protected $processingFolders = array();

	/**
	 * Pre-processes a task and executes the ICC profile transformation.
	 * A new file with a 'FalProfile_' prefix is created in the processing folder.
	 * The regular processing task is then based on the new transformed file.
	 *
	 * @param \TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService
	 * @param \TYPO3\CMS\Core\Resource\Driver\AbstractDriver $driver
	 * @param \TYPO3\CMS\Core\Resource\ProcessedFile $processedFile
	 * @param \TYPO3\CMS\Core\Resource\FileInterface $file
	 * @param string $context
	 * @param array $configuration
	 * @return void
	 */
	public function preProcess(
		\TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService,
		\TYPO3\CMS\Core\Resource\Driver\AbstractDriver $driver,
		\TYPO3\CMS\Core\Resource\ProcessedFile $processedFile,
		\TYPO3\CMS\Core\Resource\FileInterface $file,
		$context, array $configuration
	) {

		/** @var $task \TYPO3\CMS\Core\Resource\Processing\AbstractTask */
		$task = $processedFile->getTask();
		$storage = $processedFile->getStorage();
		$storageConfiguration = $storage->getConfiguration();

		if (
			empty($storageConfiguration[Bootstrap::CONFIGURATION_Scope]['enable']) ||
			empty($storageConfiguration[Bootstrap::CONFIGURATION_Scope]['source']) ||
			empty($storageConfiguration[Bootstrap::CONFIGURATION_Scope]['target'])
		) {
			return NULL;
		}

		$sourceProfile = $this->getUploadFolder() . $storageConfiguration[Bootstrap::CONFIGURATION_Scope]['source'];
		$targetProfile = $this->getUploadFolder() . $storageConfiguration[Bootstrap::CONFIGURATION_Scope]['target'];

		$targetFileName = 'FalProfile_' . $file->getName();
		$processingFolder = $this->getProcessingFolder($driver, $storage);
		$targetFile = NULL;

		// Find existing transformation result
		if ($processingFolder->hasFile($targetFileName)) {
			$targetFile = $storage->getFile(
				$processingFolder->getIdentifier() . $targetFileName
			);
			// Update source file since it will be used
			// to determine changes in ProcessedFile::needsReprocessing()
			$task->setSourceFile($targetFile);
		}

		// Create transformation if it does not exist or seems to be out-dated
		if ($targetFile === NULL || $processedFile->needsReprocessing()) {
			// Create a new/empty file object
			$targetFile = $driver->createFile($targetFileName, $processingFolder);
			// Get a temporary file name that will replace the empty object later
			$temporaryTargetFileName = $targetFile->getForLocalProcessing(TRUE);

			$parameters = array(
				'-profile ' . \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($sourceProfile),
				'-profile ' . \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($targetProfile),
			);

			// Trigger ImageMagick to execute the profile transformation
			$this->getGraphicalFunctions()->imageMagickExec(
				$file->getForLocalProcessing(FALSE),
				$temporaryTargetFileName,
				implode(' ', $parameters)
			);

			// Replace the empty file object with the actual transformed data
			$storage->replaceFile($targetFile, $temporaryTargetFileName);
		}

		// Set the task's source file that will be used to
		// continue further processing (e.g. resizing an image)
		$task->setSourceFile($targetFile);
	}

	/**
	 * Creates the custom processing folder per storage.
	 *
	 * @param \TYPO3\CMS\Core\Resource\Driver\AbstractDriver $driver
	 * @param \TYPO3\CMS\Core\Resource\ResourceStorage $storage
	 * @return \TYPO3\CMS\Core\Resource\Folder
	 */
	protected function getProcessingFolder(
		\TYPO3\CMS\Core\Resource\Driver\AbstractDriver $driver,
		\TYPO3\CMS\Core\Resource\ResourceStorage $storage
	) {

		if (!isset($this->processingFolders[$storage->getUid()])) {
			$processingFolder = '/' . trim(self::DEFAULT_ProcessingFolder, '/') . '/';

			if ($driver->folderExists($processingFolder) === FALSE) {
				$folderParts = explode('/', $processingFolder);
				$parentFolder = $driver->getRootLevelFolder();

				foreach ($folderParts as $folderPart) {
					if ($folderPart === '') {
						continue;
					}

					if (!$driver->folderExistsInFolder($folderPart, $parentFolder)) {
						$parentFolder = $driver->createFolder($folderPart, $parentFolder);
					} else {
						$parentFolder = $parentFolder->getSubfolder($folderPart);
					}
				}
			}

			$this->processingFolders[$storage->getUid()] = $driver->getFolder($processingFolder);
		}

		return $this->processingFolders[$storage->getUid()];
	}

	/**
	 * @return string
	 */
	protected function getUploadFolder() {
		return 'uploads/fal_profile/';
	}

	/**
	 * @return \TYPO3\CMS\Core\Imaging\GraphicalFunctions
	 */
	protected function getGraphicalFunctions() {
		if (!isset($this->graphicalFunctions)) {
			$this->graphicalFunctions = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions'
			);
		}

		return $this->graphicalFunctions;
	}

	/**
	 * @return \TYPO3\CMS\Core\Resource\ProcessedFileRepository
	 */
	protected function getProcessedFileRepository() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Core\\Resource\\ProcessedFileRepository'
		);
	}

}
?>