<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Support\License\Attributes;
use App\Models\Support\License\Relationships;
use App\Models\Support\License\Scopes;

class License extends Model
{
    use HasFactory,
        Attributes,
        Scopes,
        Relationships;

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
        'verified_at' => 'datetime',
        'expiration_date' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_license_verified',
        'is_license_expired'
    ];
}
