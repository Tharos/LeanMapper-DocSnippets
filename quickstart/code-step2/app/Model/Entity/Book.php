<?php

namespace Model\Entity;

/**
 * @property int $id
 *
 * @property Author $author m:hasOne
 * @property Author|null $reviewer m:hasOne(reviewer_id)
 * @property Tag[] $tags m:hasMany
 *
 * @property string $pubdate
 * @property string $name
 * @property string|null $description
 * @property string|null $website
 * @property bool $available = true
 */
class Book extends \LeanMapper\Entity
{
}