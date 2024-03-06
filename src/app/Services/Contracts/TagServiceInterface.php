<?php

namespace App\Services\Contracts;

interface TagServiceInterface
{
    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request);
}
