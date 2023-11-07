<?php

namespace App\Models\Support\Post;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Models\User;

trait Scopes
{
    /**
     * Append match count.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMatchesCount(Builder $query): Builder
    {
        return $query->selectRaw(
            DB::raw("(
            select count(distinct tb1.taggable_id) as matches_count from taggables as tb1
            where tb1.taggable_id != posts.id
            and tb1.tag_id in (
                select tb2.tag_id from taggables as tb2
                where tb2.taggable_id = posts.id
            )) as matches_count")
        );
    }

    /**
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        $userModel = (new User())->getConnectionName();
        $authDb = config("database.connections.$userModel.database");

        return $query->whereNull('deleted_at')
            ->whereHas('user', function ($query) use ($authDb) {
                $query->from("$authDb.users")->verified();
            });
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $search = null): Builder
    {
        $userId = optional(request()->user())->id;

        $userModel = (new User)->getConnectionName();
        $authDb = config("database.connections.$userModel.database");

        $query = $query->withMatchesCount()
            ->leftJoin("$authDb.connections as c1", 'c1.connection_user_id', 'posts.user_id')
            ->leftJoin("$authDb.follows as f1", 'f1.follow_user_id', 'posts.user_id');

        if ($search) {
            $query = $query->where('posts.content', 'LIKE', "%$search%");
        } else {
            $query = $query->where('posts.user_id', $userId)
                ->orWhere('c1.user_id', $userId)
                ->orWhere('f1.user_id', $userId);
        }

        return $query->groupBy(['posts.id'])
            ->orderBy('posts.updated_at', 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.follows")
                    ->whereRaw('`follows`.follow_user_id = `posts`.user_id')
                    ->whereRaw('`follows`.user_id = ?', [$userId])
                    ->select('posts.updated_at');
            }, 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.connections")
                    ->whereRaw('`connections`.connection_user_id = `posts`.user_id')
                    ->whereRaw('`connections`.user_id = ?', [$userId])
                    ->select('posts.updated_at');
            }, 'desc');
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param collection $tags
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchMatches(Builder $query, $tags, $search = null): Builder
    {
        $tagNames = $tags->pluck('name')->all();
        $tagIds = $tags->pluck('id')->all();

        $userId = optional(request()->user())->id;

        $userModel = (new User)->getConnectionName();
        $authDb = config("database.connections.$userModel.database");

        return $query->withCount([
            'tags as match_tags_count' => function ($query) use ($tagIds) {
                $query->whereIn('id', $tagIds);
            }
        ])
            ->withAnyTags($tagNames)
            ->where('posts.id', '!=', optional(request()->user())->id)
            ->where('posts.content', 'LIKE', "%$search%")
            ->groupBy(['posts.id'])
            ->orderBy('match_tags_count', 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.connections")
                    ->whereRaw('`connections`.connection_user_id = `posts`.user_id')
                    ->whereRaw('`connections`.user_id = ?', [$userId])
                    ->select('posts.updated_at');
            }, 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.follows")
                    ->whereRaw('`follows`.follow_user_id = `posts`.user_id')
                    ->whereRaw('`follows`.user_id = ?', [$userId])
                    ->select('posts.updated_at');
            }, 'desc')
            ->orderBy('posts.updated_at', 'desc');
    }
}
