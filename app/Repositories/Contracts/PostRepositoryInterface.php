<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    CreateInterface as Create,
    UpdateInterface as Update,
    DeleteInterface as Delete
};

interface PostRepositoryInterface extends Create, Update, Delete
{
    /**
     * Get paginated pin posts
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pins();

    /**
     * Get paginated shared posts
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function shares();
}
