<?php

namespace App\Services\Support\Traits\Post;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Http\Resources\PostResource;
use App\Models\Post;

trait Shareable
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function share(Post $post)
    {
        $post = $this->repository->create([
            'uuid' => Str::uuid(),
            'user_id' => request()->user()->id,
            'content' => $post->is_shared ?
                $this->repository->find($post->content['id'])->toArray() :
                $post->toArray()
        ]);

        request()->user()->shares()->attach($post);

        return response()->json(new PostResource($post));
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchShares(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            request()
                ->user()
                ->shares()
                ->withMatchesCount()
                ->where('content', 'LIKE', "%$search%")
                ->orderBy('shares.created_at', 'desc')
                ->simplePaginate()
        );
    }
}
