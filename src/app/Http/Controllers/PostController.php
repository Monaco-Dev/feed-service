<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PostServiceInterface;
use App\Http\Requests\Post\{
    StoreRequest,
    UpdateRequest,
    DestroyRequest,
    PinRequest,
    UnpinRequest,
    ShareRequest,
};
use App\Http\Requests\SearchRequest;
use App\Models\Post;
use App\Models\User;

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
     * @param  string $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(string $uuid)
    {
        return $this->service->show($uuid);
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
     * @param  \App\Http\Requests\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(SearchRequest $request)
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
     * @param  \App\Http\Requests\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPins(SearchRequest $request)
    {
        return $this->service->searchPins($request->validated());
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchShares(SearchRequest $request)
    {
        return $this->service->searchShares($request->validated());
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\SearchRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function searchWall(SearchRequest $request, User $user)
    {
        $this->authorizeForUser(request()->user(), 'search-wall-post', $user);

        return $this->service->searchWall($request->validated(), $user);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\SearchRequest  $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function searchMatches(SearchRequest $request, Post $post)
    {
        $this->authorizeForUser(request()->user(), 'search-matches-post', $post);

        return $this->service->searchMatches($request->validated(), $post);
    }
}
