<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Post extends Model
{
    use HasFactory,
        SoftDeletes,
        CascadeSoftDeletes;

    /**
     * The relationships that are soft deletable.
     * 
     * @var array<string>
     */
    protected $cascadeDeletes = [
        'shares',
        'pins'
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
     * Return Share relationship.
     * 
     * @return App\Models\Share
     */
    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Return Pin relationship.
     * 
     * @return App\Models\Pin
     */
    public function pin()
    {
        return $this->hasMany(Pin::class);
    }
}
