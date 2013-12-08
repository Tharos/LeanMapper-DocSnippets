<?php

namespace Model\Entity;

use BadMethodCallException;
use InvalidArgumentException;
use LeanMapper\Row;
use Traversable;
use Util\Mailer;
use Util\TokenGenerator;

/**
 * @property int $id
 * @property string $name
 * @property string $email m:passThru(|handleNewEmail)
 * @property string $born
 * @property-read bool $hasVerifiedEmail m:useMethods(hasVerifiedEmail)
 * @property-read int $ageInYears m:useMethods
 */
class Reader extends \LeanMapper\Entity
{

	/** @var TokenGenerator */
	private $tokenGenerator;

	/** @var Mailer */
	private $mailer;


	/**
	 * @param TokenGenerator $tokenGenerator
	 * @param Mailer $mailer
	 * @param Row|Traversable|array|null $arg
	 */
	public function __construct(TokenGenerator $tokenGenerator, Mailer $mailer, $arg = null)
	{
		parent::__construct($arg);
		$this->tokenGenerator = $tokenGenerator;
		$this->mailer = $mailer;
	}

	/**
	 * @return int
	 */
	public function getAgeInYears()
	{
		return date_diff(date_create(), date_create($this->born))->y;
	}

	/**
	 * @return bool
	 */
	public function hasVerifiedEmail()
	{
		return (bool) $this->row->emailVerified;
	}

	/**
	 * @param string $verificationToken
	 * @throws InvalidArgumentException
	 * @throws BadMethodCallException
	 */
	public function verifyEmail($verificationToken)
	{
		if ($this->hasVerifiedEmail()) {
			throw new BadMethodCallException("E-mail of user $this->name has already been verified.");
		}
		if (!isset($this->row->verificationToken) or $this->row->verificationToken !== $verificationToken) {
			throw new InvalidArgumentException("Invalid e-mail verification token given for user $this->name.");
		}
		$this->row->emailVerified = true;
		$this->row->verificationToken = null;
	}

	public function askForEmailVerification()
	{
		if ($this->hasVerifiedEmail()) {
			throw new BadMethodCallException("E-mail of user $this->name has already been verified.");
		}
		$this->mailer->sendEmail($this->email, 'Library e-mail verification', "Hi $this->name,\n\nplease verify your e-mail by code {$this->row->verificationToken}.\n\nThank you");
	}

	/**
	 * @param string $email
	 */
	protected function handleNewEmail($email)
	{
		$this->validateEmail($email);
		if (!isset($this->email) or $this->email !== $email) {
			$this->discreditEmail($this->tokenGenerator->generateToken());
		}
	}

	/**
	 * @param string $email
	 * @return string mixed
	 * @throws InvalidArgumentException
	 */
	protected function validateEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException("Invalid e-mail address given: $email.");
		}
	}

	/**
	 * @param string $newVerificationToken
	 */
	private function discreditEmail($newVerificationToken)
	{
		$this->row->emailVerified = false;
		$this->row->verificationToken = $newVerificationToken;
	}

}