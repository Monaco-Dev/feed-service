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

    /**
     * Return Post Relationship.
     * 
     * @return App\Models\Post
     */
    public function posts()
    {
        return $this->hasManyThrough(
            Post::class,
            Taggable::class,
            'tag_id',
            'id',
            'id',
            'taggable_id'
        );
    }
}
