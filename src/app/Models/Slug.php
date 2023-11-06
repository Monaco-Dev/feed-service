<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Support\Slug\Relationships;
use App\Models\Support\Slug\Scopes;

class Slug extends Model
{
    use HasFactory, Scopes, Relationships;

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'auth_mysql';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean'
    ];
}
