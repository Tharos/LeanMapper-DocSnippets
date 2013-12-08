<?php

use LeanMapper\Connection;
use LeanMapper\DefaultEntityFactory;
use LeanMapper\DefaultMapper;

require_once __DIR__ . '/vendor/SplClassLoader.php';
require_once __DIR__ . '/vendor/dibi.min.php';
require_once __DIR__ . '/vendor/LeanMapper/loader.php';

// zaregistrujeme class loader
$classLoader = new SplClassLoader(null, __DIR__);
$classLoader->register();

// vytvoříme pracovní SQLite databázi zkopírováním referenční
copy(__DIR__ . '/db/quickstart-reference.sq3', __DIR__ . '/db/quickstart.sq3');

// vytvoření instancí základních tříd Lean Mapperu
$connection = new Connection([
	'driver' => 'sqlite3',
	'database' => __DIR__ . '/db/quickstart.sq3',
]);
$mapper = new DefaultMapper;
$entityFactory = new DefaultEntityFactory;