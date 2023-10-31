<?php

namespace App\Services\Support\BaseContracts;

interface ShowInterface
{
    /**
     * Display the specified resource.
     *
     * @param mixed $model
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show(mixed $model, bool $findOrFail = true);
}
