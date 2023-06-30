<?php

namespace App\Repositories\Contracts;

use App\Repositories\Support\BaseContracts\{
    FindInterface as Find,
    DeleteInterface as Delete,
    FirstOrCreateInterface as FirstOrCreate
};

interface ShareRepositoryInterface extends Find, Delete, FirstOrCreate
{
    /**
     * Here you insert custom functions.
     */
}
