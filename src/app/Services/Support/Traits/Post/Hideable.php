<?php

namespace App\Services\Support\Traits\Post;

use App\Http\Resources\PostResource;

use App\Models\Post;

trait Hideable
{
    /**
     * Hide the specified resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function hide(Post $post)
    {
        request()->user()->hiddenPosts()->attach($post);

        return new PostResource($post);
    }

    /**
     * Unhide the specified resource in storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function unhide(Post $post)
    {
        request()->user()->hiddenPosts()->detach($post);

        return new PostResource($post);
    }
}
