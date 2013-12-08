<?php

use Model\Entity\Reader;

require_once __DIR__ . '/app/bootstrap.php';

$connection->onEvent[] = function ($event) {
	echo $event->sql, "\n";
};

$reader = new Reader;
$reader->email = 'john@doe.com';

echo $reader->email;