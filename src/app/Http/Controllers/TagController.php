<?php

namespace App\Http\Controllers;

use App\Services\Contracts\TagServiceInterface;
use App\Http\Requests\Tag\SearchRequest;

class TagController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\TagServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\TagServiceInterface $service
     */
    public function __construct(TagServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Tag\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request)
    {
        return $this->service->search($request->validated());
    }
}
