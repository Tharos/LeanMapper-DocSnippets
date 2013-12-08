<?php

namespace Util;

class Mailer
{

	/**
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 */
	public function sendEmail($to, $subject, $message)
	{
		mail($to, $subject, $message, 'From: library@leanmapper.com');
	}
	
}
 