<?php

namespace Model\Repository;

use Model\Entity\Reader;

/**
 * @author Vojtěch Kohout
 */
class ReaderRepository extends Repository
{

	protected function initEvents()
	{
		$this->onBeforePersist[] = function (Reader $reader) {
			$modifiedRowData = $reader->getModifiedRowData();
			if (isset($modifiedRowData['email'])) {
				$reader->askForEmailVerification();
			}
		};
	}

}