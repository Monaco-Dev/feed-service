<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PostServiceInterface;
use App\Http\Requests\Post\{
    StoreRequest,
    UpdateRequest,
    DestroyRequest,
    SearchPostsRequest,
    ShowRequest,
    PinRequest,
    UnpinRequest,
    ShareRequest,
    SearchPinsRequest,
    SearchSharesRequest,
};
use App\Models\Post;

class PostController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\PostServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\PostServiceInterface $service
     */
    public function __construct(PostServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\Post\ShowRequest $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Post $post)
    {
        return $this->service->show($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\UpdateRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Post $post)
    {
        return $this->service->update($post, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Post\DestroyRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Post $post)
    {
        return $this->service->destroy($post);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Post\SearchPostsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(SearchPostsRequest $request)
    {
        return $this->service->searchPosts($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\PinRequest  $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function pin(PinRequest $request, Post $post)
    {
        return $this->service->pin($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Post\UnpinRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function unpin(UnpinRequest $request, Post $post)
    {
        return $this->service->unpin($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\ShareRequest  $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function share(ShareRequest $request, Post $post)
    {
        return $this->service->share($post);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Post\SearchPinsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPins(SearchPinsRequest $request)
    {
        return $this->service->searchPins($request->validated());
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Post\SearchSharesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchShares(SearchSharesRequest $request)
    {
        return $this->service->searchShares($request->validated());
    }
}
