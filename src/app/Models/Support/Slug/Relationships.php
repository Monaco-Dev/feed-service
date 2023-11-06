<?php

namespace App\Models\Support\Slug;

use App\Models\User;

trait Relationships
{
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
