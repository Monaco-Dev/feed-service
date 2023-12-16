<?php

namespace App\Models\Support\Post;

use Illuminate\Database\Eloquent\Builder;

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
        return $query
            ->selectRaw("
                (
                    select count(*) from posts as p1
                    where p1.user_id != posts.user_id
                    and p1.content->'$.type' = 
                        IF(
                            posts.content->'$.type' = 'FS', 
                            'WTB', 
                            IF(
                                posts.content->'$.type' = 'WTB',
                                'FS',
                                IF(
                                    posts.content->'$.type' = 'FL',
                                    'WTL',
                                    IF(
                                        posts.content->'$.type' = 'WTL',
                                        'FL',
                                        null
                                    )
                                )
                            )
                        )
                    and exists (
                        select * from taggables as tb1
                        where tb1.taggable_id = p1.id
                        and tb1.tag_id in (
                            select tb2.tag_id from taggables as tb2
                            where tb2.taggable_id = posts.id
                        )
                    )
                    and exists (
                        select * from monaco_auth.users as test1
                        where test1.id = p1.user_id
                        and test1.email_verified_at is not null
                        and test1.deactivated_at is null
                        and test1.deleted_at is null
                    )
                ) as matches_count
            ");
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
            $query = $query->where(function ($query) use ($userId) {
                $query->where('posts.user_id', $userId)
                    ->orWhere('c1.user_id', $userId)
                    ->orWhere('f1.user_id', $userId);
            });
        }

        return $query
            ->verified()
            ->groupBy(['posts.id'])
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
     * @param App\Models\Post $post
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchMatches(Builder $query, $post, $search = null): Builder
    {
        $tags = $post->tags;
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
            ->where('posts.user_id', '!=', optional(request()->user())->id)
            ->where('posts.content', 'LIKE', "%$search%")
            ->whereRaw("
                (
                    select p1.content->'$.type' as type from posts as p1
                    where p1.id = $post->id
                ) =
                    IF(
                        posts.content->'$.type' = 'FS', 
                        'WTB', 
                        IF(
                            posts.content->'$.type' = 'WTB',
                            'FS',
                            IF(
                                posts.content->'$.type' = 'FL',
                                'WTL',
                                IF(
                                    posts.content->'$.type' = 'WTL',
                                    'FL',
                                    null
                                )
                            )
                        )
                    )
            ")
            ->verified()
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
