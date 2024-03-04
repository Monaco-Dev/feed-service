<?php

namespace App\Models\Support\Post;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use App\Models\Tag;
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
        $userId = optional(request()->user())->id;

        return $query
            ->selectRaw("
                IF(posts.user_id = $userId,
                    (
                        select count(*) from posts as p1
                        where p1.user_id != posts.user_id
                        and p1.deleted_at is null
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
                                            IF(
                                                posts.content->'$.type' = 'FR',
                                                'WTR',
                                                IF(
                                                    posts.content->'$.type' = 'WTR',
                                                    'FR',
                                                    null
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        and exists (
                            select * from taggables as tb1
                            where tb1.taggable_id = p1.id
                            and tb1.tag_id in (
                                select tb2.tag_id from taggables as tb2

                                left join tags as tb3
                                on tb3.id = tb2.tag_id

                                left join taggables as tb4
                                on tb4.taggable_id = posts.id

                                left join tags as tb5
                                on tb5.id = tb4.tag_id

                                where tb3.slug->'$.en' = tb5.slug->'$.en'
                            )
                        )
                        and exists (
                            select * from monaco_auth.users as tbu1
                            where tbu1.id = p1.user_id
                            and tbu1.email_verified_at is not null
                            and tbu1.deactivated_at is null
                            and tbu1.deleted_at is null
                        )
                        and exists (
                            select * from monaco_auth.licenses as tb_l1
                            where tb_l1.user_id = p1.user_id
                            and tb_l1.type is not null
                            and tb_l1.verified_at is not null
                            and tb_l1.expiration_date > NOW()
                            and tb_l1.deleted_at is null
                        )
                    ),
                    0
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
        if (App::runningUnitTests()) return $query->whereNull('deleted_at');


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
            ->selectRaw('pins.created_at as pinned_at')
            ->leftJoin("$authDb.connections as c1", 'c1.connection_user_id', 'posts.user_id')
            ->leftJoin("$authDb.follows as f1", 'f1.follow_user_id', 'posts.user_id')
            ->leftJoin('pins', function ($join) use ($userId) {
                $join->on('pins.user_id', DB::raw("'$userId'"))
                    ->on('pins.post_id', 'posts.id');
            });

        if ($search) {
            $keywords = explode(' ', $search);

            $query = $query;

            foreach ($keywords as $keyword) {
                $query = $query->whereRaw('lower(posts.content) like ?', ['%' . mb_strtolower($keyword) . '%']);
            }
        } else {
            $query = $query->where(function ($query) use ($userId) {
                $query->where('posts.user_id', $userId)
                    ->orWhere('c1.user_id', $userId)
                    ->orWhere('f1.user_id', $userId);
            });
        }

        return $query->verified()
            ->groupBy(['posts.id', 'pinned_at'])
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
    public function scopeSearchMatches(Builder $query, $post, $search = null, $onlyPins = false): Builder
    {
        $tags = $post->tags;

        $tagValues = [
            ...$tags->pluck('name')->all(),
            ...$tags->pluck('slug')->all()
        ];

        $tags = Tag::where(function ($query) use ($tagValues) {
            if (count($tagValues)) $query->containing($tagValues[0]);
        });
        foreach ($tagValues as $tag) {
            $tags->orWhere(function ($query) use ($tag) {
                $query->containing($tag);
            });
        }
        $tags = $tags->get();

        $tagIds = $tags->pluck('id')->all();
        $tagValues = [
            ...$tags->pluck('name')->all(),
            ...$tags->pluck('slug')->all()
        ];

        $userId = optional(request()->user())->id;

        $userModel = (new User)->getConnectionName();
        $authDb = config("database.connections.$userModel.database");

        $query = $query->selectRaw('pins.created_at as pinned_at')
            ->withCount([
                'tags as match_tags_count' => function ($query) use ($tagIds) {
                    $query->whereIn('id', $tagIds);
                }
            ])
            ->withAnyTags($tagValues)
            ->leftJoin('pins', function ($join) use ($userId) {
                $join->on('pins.user_id', DB::raw("'$userId'"))
                    ->on('pins.post_id', 'posts.id');
            })
            ->where('posts.user_id', '!=', $userId)
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
                                    IF(
                                        posts.content->'$.type' = 'FR',
                                        'WTR',
                                        IF(
                                            posts.content->'$.type' = 'WTR',
                                            'FR',
                                            null
                                        )
                                    )
                                )
                            )
                        )
                    )
            ");

        if ($onlyPins) {
            $query = $query->whereHas('pins', function ($query) use ($userId) {
                $query->whereUserId($userId);
            });
        }

        $query = $query->verified()
            ->groupBy(['posts.id', 'pinned_at'])
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

        return $query;
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchArchives(Builder $query, $search = null): Builder
    {
        $userId = optional(request()->user())->id;

        return $query->withTrashed()
            ->whereNotNull('deleted_at')
            ->whereUserId($userId)
            ->where('posts.content', 'LIKE', "%$search%")
            ->orderBy('posts.updated_at', 'desc');
    }
}
