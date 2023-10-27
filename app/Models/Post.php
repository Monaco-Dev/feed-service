<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The relationship counts that should be eager loaded on every query.
     * 
     * @var array<string>
     */
    protected $withCount = [
        'shares'
    ];

    /**
     * The relations to eager load on every query.
     * 
     * @var array<string>
     */
    protected $with = [
        'user'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content'
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_verified',
        'pinned_at'
    ];

    /**
     * Return Share relationship.
     * 
     * @return App\Models\Share
     */
    public function shares()
    {
        return $this->setConnection('mysql')->hasMany(Share::class);
    }

    /**
     * Return Pin relationship.
     * 
     * @return App\Models\Pin
     */
    public function pins()
    {
        return $this->setConnection('mysql')->hasMany(Pin::class);
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->setConnection('auth_mysql')->belongsTo(User::class);
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsVerifiedAttribute()
    {
        return !!$this->user->verified()->find($this->user_id);
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getPinnedAtAttribute()
    {
        return optional(
            $this->pins()->where(
                'user_id',
                optional(request()->user())->id
            )->first()
        )->created_at;
    }

    /**
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        $userModel = (new User)->getConnectionName();
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

        $query = $query->leftJoin("$authDb.connections as c1", 'c1.connection_user_id', 'posts.user_id')
            ->leftJoin("$authDb.follows as f1", 'f1.follow_user_id', 'posts.user_id');

        if ($search) {
            $query = $query->where('posts.content', 'LIKE', "%$search%");
        } else {
            $query = $query->where('posts.user_id', $userId)
                ->orWhere('c1.user_id', $userId)
                ->orWhere('f1.user_id', $userId);
        }

        return $query->groupBy(['posts.id'])
            ->orderBy('posts.created_at', 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.follows")
                    ->whereRaw('`follows`.follow_user_id = `posts`.user_id')
                    ->whereRaw('`follows`.user_id = ?', [$userId])
                    ->select('posts.created_at');
            }, 'desc')
            ->orderBy(function ($query) use ($authDb, $userId) {
                return $query->from("$authDb.connections")
                    ->whereRaw('`connections`.connection_user_id = `posts`.user_id')
                    ->whereRaw('`connections`.user_id = ?', [$userId])
                    ->select('posts.created_at');
            }, 'desc');
    }
}
