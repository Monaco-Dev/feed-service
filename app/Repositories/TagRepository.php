<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagRepository extends Repository implements TagRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Tag
     */
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }
}
