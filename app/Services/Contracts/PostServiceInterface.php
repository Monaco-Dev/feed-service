<?php

namespace App\Services\Contracts;

use App\Services\Support\BaseContracts\{
    StoreInterface as Store,
    ShowInterface as Show,
    UpdateInterface as Update,
    DestroyInterface as Destroy
};

interface PostServiceInterface extends Store, Show, Update, Destroy
{
    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request);
}
