<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository extends Repository implements PostRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Post
     */
    public function __construct(Post $model)
    {
        $this->model = $model;
    }
}
