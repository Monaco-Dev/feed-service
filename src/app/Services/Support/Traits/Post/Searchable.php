<?php

namespace App\Services\Support\Traits\Post;

use Illuminate\Support\Arr;

use App\Models\Post;
use App\Models\User;

trait Searchable
{
    /**
     * Search for specific resources in the database.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            $this->repository
                ->model()
                ->search($search)
                ->paginate()
        );
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function searchWall(array $request, User $user)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            $user->posts()
                ->withMatchesCount()
                ->where('content', 'LIKE', "%$search%")
                ->orderBy('posts.created_at', 'desc')
                ->paginate()
        );
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function searchMatches(array $request, Post $post)
    {
        $search = Arr::get($request, 'search');
        $onlyPins = Arr::get($request, 'only_pins');

        return $this->setResponseCollection(
            $this->repository->model()
                ->searchMatches($post, $search, $onlyPins)
                ->paginate()
        );
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchArchives(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            $this->repository->model()
                ->searchArchives($search)
                ->paginate()
        );
    }
}
