<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PinServiceInterface;
use App\Http\Requests\Pin\{
    StoreRequest,
    DestroyRequest
};

class PinController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\PinServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\PinServiceInterface $service
     */
    public function __construct(PinServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Pin\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Pin\DestroyRequest  $request
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return $this->service->destroy($id);
    }
}
