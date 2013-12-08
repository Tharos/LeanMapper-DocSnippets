<?php

namespace Model\Entity;

use InvalidArgumentException;
use LeanMapper\Row;
use Traversable;
use Util\Mailer;

/**
 * @property int $id
 * @property Book $book m:hasOne m:passThru(checkWhileSettingBook)
 * @property Reader $reader m:hasOne m:passThru(checkWhileSettingReader)
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
	 * @param Book $book
	 * @return Book
	 */
	public function checkWhileSettingBook(Book $book)
	{
		if (isset($this->reader)) {
			$this->checkAgeAndAlcohol($book, $this->reader);
		}
		return $book;
	}

	/**
	 * @param Reader $reader
	 * @return Reader
	 * @throws InvalidArgumentException
	 */
	public function checkWhileSettingReader(Reader $reader)
	{
		if (!$reader->hasVerifiedEmail) {
			throw new InvalidArgumentException("E-mail of $reader->name was not verified.");
		}
		if (isset($this->book)) {
			$this->checkAgeAndAlcohol($this->book, $reader);
		}
		return $reader;
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