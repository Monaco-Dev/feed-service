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

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id);

    /**
     * Search for specific resources in the database.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request);
}
