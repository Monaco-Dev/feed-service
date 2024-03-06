<?php

namespace App\Models\Support\Share;

use App\Models\Post;
use App\Models\User;

trait Relationships
{
    /**
     * Return Post relationship.
     * 
     * @return App\Models\Post
     */
    public function post()
    {
        return $this->setConnection('mysql')->belongsTo(Post::class);
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class);
    }
}
