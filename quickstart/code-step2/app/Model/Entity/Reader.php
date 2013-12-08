<?php

namespace Model\Entity;

use InvalidArgumentException;

/**
 * @property int $id
 * @property string $name
 * @property string $email m:passThru(validateEmail)
 * @property string $born
 */
class Reader extends \LeanMapper\Entity
{

	/**
	 * @param string $email
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function validateEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException("Invalid e-mail address given: $email.");
		}
		return $email;
	}

}