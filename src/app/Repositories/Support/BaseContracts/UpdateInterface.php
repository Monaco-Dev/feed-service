<?php

namespace App\Repositories\Support\BaseContracts;

interface UpdateInterface
{
    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return mixed
     */
    public function update(mixed $model, array $request);
}
