<?php

namespace Model\Entity;

use InvalidArgumentException;
use LeanMapper\Row;
use Traversable;
use Util\Mailer;

/**
 * @property int $id
 * @property Book $book m:hasOne m:useMethods
 * @property Reader $reader m:hasOne m:useMethods
 * @property string $borrowed
 */
class Borrowing extends \LeanMapper\Entity
{

	const ALCOHOL_AGE_LIMIT = 18;

	const BORROWING_DAYS_LIMIT = 365;

	/** @var Mailer */
	private $mailer;


	/**
	 * @param Mailer $mailer
	 * @param Row|Traversable|array|null $arg
	 */
	public function __construct(Mailer $mailer, $arg = null)
	{
		$this->mailer = $mailer;
		parent::__construct($arg);
	}

	/**
	 * @return Book
	 */
	public function getBook()
	{
		return $this->__get('book');
	}

	/**
	 * @param Book $book
	 */
	public function setBook(Book $book)
	{
		if (isset($this->reader)) {
			$this->checkAgeAndAlcohol($book, $this->reader);
		}
		$this->__set('book', $book);
	}

	/**
	 * @return Reader
	 */
	public function getReader()
	{
		return $this->__get('reader');
	}

	/**
	 * @param Reader $reader
	 * @throws InvalidArgumentException
	 */
	public function setReader(Reader $reader)
	{
		if (!$reader->hasVerifiedEmail) {
			throw new InvalidArgumentException("E-mail of $reader->name was not verified.");
		}
		if (isset($this->book)) {
			$this->checkAgeAndAlcohol($this->book, $reader);
		}
		$this->__set('reader', $reader);
	}

	public function remindForgetfulReader()
	{
		$borrowingDays = floor((time() - strtotime($this->borrowed)) / (60 * 60 * 24));
		if ($borrowingDays > self::BORROWING_DAYS_LIMIT) {
			$this->mailer->sendEmail($this->reader->email, 'Library reminder', "Hi {$this->reader->name},\n\nplease return book {$this->book->name} to your library.\n\nThank you");
		}
	}

	/**
	 * @param Book $book
	 * @param Reader $reader
	 * @throws InvalidArgumentException
	 */
	private function checkAgeAndAlcohol(Book $book, Reader $reader)
	{
		if ($book->isAboutAlcohol and $reader->ageInYears <= self::ALCOHOL_AGE_LIMIT) {
			throw new InvalidArgumentException("Borrowe $reader->name is not allowed to borrow book \"$book->name\" since it's about alcohol and he/she is not yet " . self::ALCOHOL_AGE_LIMIT . ' years old.');
		}
	}

}