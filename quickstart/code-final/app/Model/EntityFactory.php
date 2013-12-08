<?php

namespace Model;

use LeanMapper\Row;
use Model\Entity\Borrowing;
use Model\Entity\Reader;
use Traversable;
use Util\Mailer;
use Util\TokenGenerator;

class EntityFactory extends \LeanMapper\DefaultEntityFactory
{

	/** @var Mailer */
	private $mailer;

	/** @var TokenGenerator */
	private $tokenGenerator;


	/**
	 * @param Mailer $mailer
	 * @param TokenGenerator $tokenGenerator
	 */
	public function __construct(Mailer $mailer, TokenGenerator $tokenGenerator)
	{
		$this->mailer = $mailer;
		$this->tokenGenerator = $tokenGenerator;
	}

	/**
	 * @param string $entityClass
	 * @param Row|Traversable|array|null $arg
	 * @return Borrowing
	 */
	public function createEntity($entityClass, $arg = null)
	{
		if ($entityClass === 'Model\Entity\Borrowing') {
			return new Borrowing($this->mailer, $arg);
		}
		if ($entityClass === 'Model\Entity\Reader') {
			return new Reader($this->tokenGenerator, $this->mailer, $arg);
		}
		return parent::createEntity($entityClass, $arg);
	}
	
}
 