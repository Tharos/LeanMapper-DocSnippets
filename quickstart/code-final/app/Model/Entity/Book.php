<?php

namespace Model\Entity;

use LeanMapper\Filtering;
use LeanMapper\Fluent;

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
 * @property-read bool $isAboutAlcohol m:useMethods(isAboutAlcohol) Tells whether book has assigned tag with name alcohol
 */
class Book extends \LeanMapper\Entity
{

	/**
	 * @return bool
	 */
	public function isAboutAlcohol()
	{
		$filtering = new Filtering(function (Fluent $statement) {
			$statement->join('tag')->on('[book_tag.tag_id] = [tag.id]')
					->where('[tag.name] = "alcohol"');
		});
		$rows = $this->row->referencing('book_tag', null, $filtering);
		return !empty($rows);
	}

}