<?php

namespace App\Repositories;

use App\Models\Pin;
use App\Repositories\Contracts\PinRepositoryInterface;

class PinRepository extends Repository implements PinRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Pin
     */
    public function __construct(Pin $model)
    {
        $this->model = $model;
    }
}
