<?php

namespace App\Models\Support\Post;

use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

use App\Models\Pin;
use App\Models\Post;
use App\Models\Share;
use App\Models\User;

trait Relationships
{
    use HasJsonRelationships;

    /**
     * Return Share relationship.
     * 
     * @return App\Models\Share
     */
    public function shares()
    {
        return $this->setConnection('mysql')->hasMany(Share::class);
    }

    /**
     * Return Pin relationship.
     * 
     * @return App\Models\Pin
     */
    public function pins()
    {
        return $this->setConnection('mysql')->hasMany(Pin::class);
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->setConnection('auth_mysql')->belongsTo(User::class);
    }

    /**
     * Return Shared post relationship.
     * 
     * @return App\Models\Post
     */
    public function sharedPost()
    {
        return $this->belongsTo(Post::class, 'content->post_id');
    }
}
