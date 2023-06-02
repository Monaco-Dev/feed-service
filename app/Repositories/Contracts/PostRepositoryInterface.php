<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    CreateInterface as Create,
    FindInterface as Find,
    UpdateInterface as Update,
    DeleteInterface as Delete
};

interface PostRepositoryInterface extends Create, Find, Update, Delete
{
    /**
     * Here you insert custom functions.
     */
}
