<?php

namespace App\Repositories;

use App\Models\Share;
use App\Repositories\Contracts\ShareRepositoryInterface;

class ShareRepository extends Repository implements ShareRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Share
     */
    public function __construct(Share $model)
    {
        $this->model = $model;
    }
}
