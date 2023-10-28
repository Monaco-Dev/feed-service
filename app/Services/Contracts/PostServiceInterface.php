<?php

namespace App\Services\Contracts;

use App\Models\Post;
use App\Services\Support\BaseContracts\{
    StoreInterface as Store,
    ShowInterface as Show,
    UpdateInterface as Update,
    DestroyInterface as Destroy
};

interface PostServiceInterface extends Store, Show, Update, Destroy
{
    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(array $request);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function pin(Post $post);

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function unpin(Post $post);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPins(array $request);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function share(Post $post);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchShares(array $request);
}
