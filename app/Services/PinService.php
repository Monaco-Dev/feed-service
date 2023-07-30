<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Http\Resources\PostResource;
use App\Repositories\Contracts\PinRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PinServiceInterface;

class PinService extends Service implements PinServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\PinRepositoryInterface
     * @param App\Repositories\Contracts\PostRepositoryInterface
     */
    public function __construct(
        PinRepositoryInterface $repository,
        PostRepositoryInterface $postRepository
    ) {
        $this->repository = $repository;
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function index()
    {
        $posts = $this->postRepository->pins();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        return $this->repository->firstOrCreate(
            [
                'post_id' => Arr::get($request, 'post_id'),
                'user_id' => Auth::user()->id
            ],
            [
                'post_id' => Arr::get($request, 'post_id'),
                'user_id' => Auth::user()->id
            ]
        );
    }
}
