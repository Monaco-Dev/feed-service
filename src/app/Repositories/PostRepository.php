<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository extends Repository implements PostRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Post
     */
    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function view(string $uuid)
    {
        return $this->model
            ->withMatchesCount()
            ->verified()
            ->whereUuid($uuid)
            ->first();
    }

    /**
     * Get paginated pinned posts
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pins()
    {
        $userId = Auth::user()->id;

        // Initial users table from auth service
        $userModel = (new User())->getConnectionName();
        $authDB = config("database.connections.$userModel.database");

        $select = [
            'posts.id',
            'posts.user_id',
            'posts.content',
            DB::raw("pins.created_at as pinned_at"),
            'posts.created_at'
        ];

        $data = $this->model
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
            ->simplePaginate();

        return $data;
    }

    /**
     * Get paginated shared posts
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function shares()
    {
        $userId = Auth::user()->id;

        // Initial users table from auth service
        $userModel = (new User)->getConnectionName();
        $authDB = config("database.connections.$userModel.database");

        $select = [
            'posts.id',
            'posts.user_id',
            'posts.content',
            DB::raw("IF(pins.user_id = $userId, pins.created_at, null) as pinned_at"),
            'posts.created_at'
        ];

        $posts = $this->model
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
            ->whereHas('shares', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->simplePaginate();

        return $posts;
    }
}
