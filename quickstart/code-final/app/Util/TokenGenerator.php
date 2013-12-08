<?php

namespace Util;

class TokenGenerator
{

	/**
	 * @return string
	 */
	public function generateToken()
	{
		return bin2hex(openssl_random_pseudo_bytes(10));
	}
	
}
 