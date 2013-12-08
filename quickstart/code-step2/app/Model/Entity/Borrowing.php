<?php

namespace Model\Entity;

/**
 * @property int $id
 * @property Book $book m:hasOne
 * @property Reader $reader m:hasOne
 * @property string $borrowed
 */
class Borrowing extends \LeanMapper\Entity
{
}