<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

use App\Http\Resources\PostResource;
use App\Services\Contracts\PostServiceInterface;
use App\Repositories\Contracts\{
    PinRepositoryInterface,
    PostRepositoryInterface,
    ShareRepositoryInterface
};

class PostService extends Service implements PostServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\ShareRepositoryInterface
     */
    protected $shareRepository;

    /**
     * @var \App\Repositories\Contracts\PinRepositoryInterface
     */
    protected $pinRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\PostRepositoryInterface
     * @param App\Repositories\Contracts\ShareRepositoryInterface
     * @param App\Repositories\Contracts\PinRepositoryInterface
     */
    public function __construct(
        PostRepositoryInterface $repository,
        ShareRepositoryInterface $shareRepository,
        PinRepositoryInterface $pinRepository
    ) {
        $this->repository = $repository;
        $this->shareRepository = $shareRepository;
        $this->pinRepository = $pinRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Http\Response
     */
    public function show($id, bool $findOrFail = true)
    {
        $post = $this->repository->show($id);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int|string $id
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, array $request)
    {
        $this->repository->update($id, $request);

        return $this->show($id);
    }

    /**
     * Search for specific resources in the database.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request)
    {
        $data = $this->repository->search($request);

        return PostResource::collection($data);
    }
}
