<?php

use Model\Entity\Book;
use Model\Entity\Reader;

require_once __DIR__ . '/app/bootstrap.php';

$connection->onEvent[] = function ($event) {
	echo $event->sql, "\n";
};

////////////////////

// vytvoříme nového čtenáře
$reader = new Reader;

// a nastavíme mu jmého
$reader->name = 'John Doe';

// pokus o přiřazení null do not-null položky skončí výjimkou (Property 'name' in entity Model\Entity\Reader cannot be null.)
$reader->name = null;

// nastavíme čtenáři e-mailovou adresu
$reader->email = 'john@doe.com';

// a pro kontrolu ji vypíšeme
echo $reader->email;

// přiřazení nevalidní e-mailové adresy vyvolá výjimku (Invalid e-mail address given: foobar.)
$reader->email = 'foobar';

// stejně tak i přiřazení hodnoty, kterou není možné automaticky převést na potřebný typ
$reader->email = new DateTime;

// můžeme také provést hromadné přiřazení hodnot do položek
$reader->assign([
	'name' => 'Jack Smith',
	'email' => 'jack@smith.com',
]);

// což lze mimochodem provést již v konstruktoru
$reader = new Reader([
	'name' => 'Jack Smith',
	'email' => 'jack@smith.com',
]);

////////////////////

$book = new Book;

// vypíše bool(true), protože položka available v entitě Book má nastavenou výchozí hodnotu true
var_dump($book->available);

// čtení neinicializovaných položek bez explicitně určených výchozích hodnot skončí výjimkou
echo $book->pubdate;