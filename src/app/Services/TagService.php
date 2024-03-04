<?php

namespace App\Services;

use Illuminate\Support\Arr;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Services\Contracts\TagServiceInterface;

class TagService extends Service implements TagServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\TagRepositoryInterface
     */
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request)
    {
        $model = Tag::withCount('taggables')->has('posts');

        if (Arr::get($request, 'search')) $model = $model->containing(Arr::get($request, 'search'));

        return $model->simplePaginate(5);
    }
}
