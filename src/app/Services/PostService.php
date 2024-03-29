<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Models\Tag;
use App\Http\Resources\PostResource;
use App\Services\Contracts\PostServiceInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Support\Traits\Post\Pinnable;
use App\Services\Support\Traits\Post\Searchable;
use App\Services\Support\Traits\Post\Shareable;

class PostService extends Service implements PostServiceInterface
{
    use Searchable, Shareable, Pinnable;

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
     * Prepare form request data.
     * 
     * @param array $request
     * @return array
     */
    private function mapRequest(array $request)
    {
        $content = Arr::get($request, 'content');
        $type = Arr::get($request, 'type');
        $tags = Arr::get($request, 'tags') ?? [];

        Arr::set($request, 'tags', $tags);
        Arr::set($request, 'user_id', optional(request()->user())->id);
        Arr::set($request, 'content', [
            'body' => $content,
            'type' => $type
        ]);

        return $request;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        $request = $this->mapRequest($request);
        Arr::set($request, 'uuid', Str::uuid());

        $post = $this->repository->create(Arr::except($request, ['tags']));

        $post->syncTags(Arr::get($request, 'tags'));

        $post = $this->repository->view($post->uuid);

        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return mixed
     */
    public function update(mixed $model, array $request)
    {
        $request = $this->mapRequest($request);

        $this->repository->update($model, Arr::except($request, ['tags']));

        $model->syncTags(Arr::get($request, 'tags'));

        Tag::doesntHave('taggables')->delete();

        $model = $this->repository->view($model->uuid);

        return response()->json(new PostResource($model));
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $uuid
     * @param bool $findOrFail
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(mixed $uuid, bool $findOrFail = true)
    {
        $data = $this->repository->view($uuid);

        return isset($data)
            ? $this->setResponseResource($data)
            : null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(mixed $model)
    {
        $tags = $model->tags()->get();

        $model->delete();

        $model->syncTags($tags);

        return response()->json(true);
    }

    /**
     * Restore the specified resource in storage.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function restore(mixed $post)
    {
        $post->restore();

        return response()->json(true);
    }
}
