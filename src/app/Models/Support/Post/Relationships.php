<?php

namespace App\Models\Support\Post;

use App\Models\Pin;
use App\Models\Share;
use App\Models\User;

trait Relationships
{
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
}
