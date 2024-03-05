<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    CreateInterface as Create,
    UpdateInterface as Update,
    DeleteInterface as Delete
};

interface PostRepositoryInterface extends Find, Create, Update, Delete
{
    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function view(string $uuid);
}
