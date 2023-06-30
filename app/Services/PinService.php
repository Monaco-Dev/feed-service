<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Resources\PostResource;
use App\Models\User;
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
        $userId = Auth::user()->id;

        // Initial users table from auth service
        $userModel = (new User)->getConnectionName();
        $authDB = config("database.connections.$userModel.database");

        $select = [
            'posts.id',
            'posts.user_id',
            'posts.content',
            DB::raw("pins.created_at as pinned_at"),
            'posts.created_at'
        ];

        $posts = $this->postRepository->model()
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
            ])
            ->leftJoin(
                'pins',
                'pins.post_id',
                '=',
                'posts.id'
            )
            ->whereHas('pins', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate();

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
