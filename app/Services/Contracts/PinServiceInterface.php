<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\{
    IndexInterface as Index,
    StoreInterface as Store,
    DestroyInterface as Destroy
};

interface PinServiceInterface extends Index, Store, Destroy
{
    /**
     * Here you insert custom functions.
     */
}
