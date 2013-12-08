<?php

namespace Model\Repository;

use InvalidArgumentException;
use LeanMapper\Entity;

/**
 * @author Vojtěch Kohout
 */
abstract class Repository extends \LeanMapper\Repository
{

	/**
	 * @param mixed $id
	 * @return Entity
	 * @throws InvalidArgumentException
	 */
	public function find($id)
	{
		$table = $this->getTable();
		$primaryKey = $this->mapper->getPrimaryKey($table);
		$row = $this->createFluent()->where('%n.%n = ?', $table, $primaryKey, $id)->fetch();
		if ($row === false) {
			$entityClass = $this->mapper->getEntityClass($table);
			throw new InvalidArgumentException("Entity $entityClass with ID $id was not found.");
		}
		return $this->createEntity($row);
	}

	/**
	 * @return Entity[]
	 */
	public function findAll()
	{
		return $this->createEntities(
			$this->createFluent()->fetchAll()
		);
	}
	
}