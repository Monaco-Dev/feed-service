<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Contracts\PostServiceInterface;
use App\Repositories\Contracts\{
    PostRepositoryInterface,
};

class PostService extends Service implements PostServiceInterface
{
    /**
     * Resource class of the service.
     * 
     * @var \App\Http\Resources\PostResource
     */
    protected $resourceClass = PostResource::class;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\PostRepositoryInterface
     */
    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        Arr::set($request, 'user_id', optional(request()->user())->id);
        Arr::set($request, 'content', [
            'body' => Arr::get($request, 'content')
        ]);

        return new PostResource(
            $this->repository->create($request)
        );
    }

    /**
     * Search for specific resources in the database.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            $this->repository
                ->model()
                ->search($search)
                ->paginate()
        );
    }

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
                ->where('content', 'LIKE', "%$search%")
                ->orderBy('pins.created_at', 'desc')
                ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function share(Post $post)
    {
        $this->repository->create([
            'user_id' => request()->user()->id,
            'content' => $post->is_shared ? $this->repository->find($post->content->id)->toArray() : $post->toArray()
        ]);

        return request()->user()->shares()->attach($post);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchShares(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            request()
                ->user()
                ->shares()
                ->where('content', 'LIKE', "%$search%")
                ->orderBy('shares.created_at', 'desc')
                ->paginate()
        );
    }
}
