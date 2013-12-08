<?php

namespace Model\Repository;

use LeanMapper\Connection;
use LeanMapper\IMapper;
use LeanMapper\IEntityFactory;
use Model\Entity\Reader;
use Util\Mailer;

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
 