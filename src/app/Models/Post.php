<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

use App\Models\Support\Post\Attributes;
use App\Models\Support\Post\Relationships;
use App\Models\Support\Post\Scopes;

class Post extends Model
{
    use HasFactory,
        SoftDeletes,
        HasTags,
        Attributes,
        Relationships,
        Scopes;

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'mysql';

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
        'uuid',
        'user_id',
        'content',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_verified',
        'pinned_at',
        'is_shared',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array'
    ];
}
