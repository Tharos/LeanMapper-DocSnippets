<?php

namespace Model\Repository;

use Model\Entity\Borrowing;

/**
 * @author Vojtěch Kohout
 */
class BorrowingRepository extends Repository
{

	/**
	 * @param int $daysLimit
	 * @return Borrowing[]
	 */
	public function findForget($daysLimit)
	{
		$limitDate = date('Y-m-d', time() - $daysLimit * 60 * 60 * 24);
		return $this->createEntities(
			$this->createFluent()->where('[borrowed] < %s', $limitDate)->fetchAll()
		);
	}

}
 