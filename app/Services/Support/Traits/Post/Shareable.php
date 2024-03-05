<?php

namespace App\Services\Support\Traits\Post;

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
        $post = $post->is_shared ?
            $this->repository->find($post->content['post_id']) :
            $post;

        request()->user()->shares()->attach($post);

        $post = $this->repository->create([
            'uuid' => Str::uuid(),
            'user_id' => request()->user()->id,
            'content' => ['post_id' => $post->id]
        ]);

        $post = $this->repository->view($post->uuid);

        return response()->json(new PostResource($post));
    }
}
