<?php

require_once __DIR__ . '/app/bootstrap.php';

$connection->onEvent[] = function ($event) {
	echo $event->sql, "\n";
};