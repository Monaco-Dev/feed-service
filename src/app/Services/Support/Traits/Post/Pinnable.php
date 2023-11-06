<?php

namespace App\Services\Support\Traits\Post;

use Illuminate\Support\Arr;

use App\Models\Post;

trait Pinnable
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function pin(Post $post)
    {
        return request()->user()->pins()->attach($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function unpin(Post $post)
    {
        return request()->user()->pins()->detach($post);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPins(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            request()
                ->user()
                ->pins()
                ->withMatchesCount()
                ->where('content', 'LIKE', "%$search%")
                ->orderBy('pins.created_at', 'desc')
                ->paginate()
        );
    }
}
