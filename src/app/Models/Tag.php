<?php

namespace App\Models;

use Spatie\Tags\Tag as TagModel;

class Tag extends TagModel
{
    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Return Taggable Relationship.
     * 
     * @return App\Models\Taggable
     */
    public function taggables()
    {
        return $this->hasMany(Taggable::class);
    }
}
