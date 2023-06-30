<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{
    App,
    DB,
    Auth
};

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
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
        $userId = Auth::user()->id;

        $userModel = (new User)->getConnectionName();
        $authDB = config("database.connections.$userModel.database");

        $post = $this->repository->model()
            ->select([
                'posts.*',
                DB::raw("IF(pins.user_id = $userId, pins.created_at, null) as pinned_at"),
            ])
            ->with([
                'shares' => function ($query) use ($authDB, $userId) {
                    $query->join(
                        "$authDB.users as users1",
                        'users1.id',
                        '=',
                        'shares.user_id'
                    )
                        ->leftJoin(
                            "$authDB.connections as connections1",
                            'connections1.user_id',
                            '=',
                            'shares.user_id'
                        )
                        ->where('connections1.connection_user_id', $userId)
                        ->take(5);
                }
            ])
            ->where('posts.id', $id)
            ->leftJoin('pins', 'pins.post_id', '=', 'posts.id')
            ->first();

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
        $userId = Auth::user()->id;
        $search = Arr::get($request, 'search');

        // Initial users table from auth service
        $userModel = (new User)->getConnectionName();
        $authDB = config("database.connections.$userModel.database");

        // Initialize posts table from auth service
        $postModel = (new Post())->getConnectionName();
        $feedDB = config("database.connections.$postModel.database");

        $select = [
            'posts.id',
            'posts.user_id',
            'posts.content',
            DB::raw("IF(pins.user_id = $userId, pins.created_at, null) as pinned_at"),
            'posts.created_at'
        ];

        $posts = $this->repository->model()
            ->select($select)
            ->with([
                // Get all users who shared a specific post
                'shares' => function ($query) use ($authDB, $userId) {
                    $query->join(
                        "$authDB.users as users1",
                        'users1.id',
                        '=',
                        'shares.user_id'
                    )
                        ->leftJoin(
                            "$authDB.connections as connections1",
                            'connections1.connection_user_id',
                            '=',
                            'shares.user_id'
                        )
                        // Take only 5 users from your connections
                        ->where('connections1.user_id', $userId)
                        ->take(5);
                }
            ])
            ->withCount([
                // Get total count of shares
                'shares'
            ]);

        if ($search) {
            // Apply search keywords
            $posts = $posts->where(function ($query) use ($search, $authDB) {
                $query->where('posts.content', 'LIKE', "%$search%")
                    ->orWhereHas('user', function ($query) use ($search, $authDB) {
                        $query->from("$authDB.users")
                            ->where('username', 'LIKE', "%$search%");
                    });
            });
        } else {
            $posts = $posts->whereHas('user', function ($query) use ($authDB, $feedDB, $userId) {
                $query->from("$authDB.users")
                    ->leftJoin(
                        "$authDB.connections as connections2",
                        'connections2.connection_user_id',
                        '=',
                        'posts.user_id'
                    )
                    ->leftJoin(
                        "$feedDB.shares as shares1",
                        'shares1.post_id',
                        '=',
                        'posts.id'
                    )
                    ->leftJoin(
                        "$authDB.connections as connections3",
                        'connections3.connection_user_id',
                        '=',
                        'shares1.user_id'
                    )
                    // Get posted by you
                    ->where('posts.user_id', $userId)
                    // Get posted by your connections
                    ->orWhere('connections2.user_id', $userId)
                    // Get shared posts by your connections
                    ->orWhere('connections3.user_id', $userId);
            });
        }

        // Identify if a specific post is pinned by you
        $posts = $posts->leftJoin(
            'pins',
            'pins.post_id',
            '=',
            'posts.id'
        )->paginate();

        return PostResource::collection($posts);
    }
}
