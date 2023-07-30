<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\PostResource;
use App\Repositories\Contracts\ShareRepositoryInterface;
use App\Services\Contracts\ShareServiceInterface;
use App\Repositories\Contracts\PostRepositoryInterface;

class ShareService extends Service implements ShareServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\ShareRepositoryInterface
     * @param App\Repositories\Contracts\PostRepositoryInterface
     */
    public function __construct(
        ShareRepositoryInterface $repository,
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
        $data = $this->postRepository->shares();

        return PostResource::collection($data);
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
