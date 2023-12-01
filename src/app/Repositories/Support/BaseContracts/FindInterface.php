<?php

namespace App\Repositories\Support\BaseContracts;

interface FindInterface
{
    /**
     * Display the specified resource.
     *
     * @param mixed $model
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(mixed $model, bool $findOrFail = true);
}
