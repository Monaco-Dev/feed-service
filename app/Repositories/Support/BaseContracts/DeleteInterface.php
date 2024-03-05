<?php

namespace App\Repositories\Support\BaseContracts;

interface DeleteInterface
{
    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return mixed
     */
    public function delete(mixed $model);
}
