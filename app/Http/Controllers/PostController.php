<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PostServiceInterface;
use App\Http\Requests\Post\{
    StoreRequest,
    UpdateRequest,
    DestroyRequest,
    SearchRequest,
    ShowRequest,
};

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
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, $id)
    {
        return $this->service->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\UpdateRequest  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Post\DestroyRequest  $request
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return $this->service->destroy($id);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Post\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request)
    {
        return $this->service->search($request->validated());
    }
}
