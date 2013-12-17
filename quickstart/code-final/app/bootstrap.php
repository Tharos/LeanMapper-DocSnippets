<?php

use LeanMapper\Connection;
use LeanMapper\DefaultMapper;
use Model\EntityFactory;
use Util\Mailer;
use Util\TokenGenerator;

require_once __DIR__ . '/vendor/SplClassLoader.php';
require_once __DIR__ . '/vendor/dibi.min.php';
require_once __DIR__ . '/vendor/LeanMapper/loader.php';

$classLoader = new SplClassLoader(null, __DIR__);
$classLoader->register();

copy(__DIR__ . '/db/quickstart-reference.sq3', __DIR__ . '/db/quickstart.sq3');

$connection = new Connection([
	'driver' => 'sqlite3',
	'database' => __DIR__ . '/db/quickstart.sq3',
]);
$mailer = new Mailer;
$tokenGenerator = new TokenGenerator;
$mapper = new DefaultMapper;
$entityFactory = new EntityFactory($mailer, $tokenGenerator);