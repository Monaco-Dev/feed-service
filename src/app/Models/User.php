<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Support\User\Attributes;
use App\Models\Support\User\Relationships;
use App\Models\Support\User\Scopes;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        Attributes,
        Relationships,
        Scopes;

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'auth_mysql';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deactivated_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_email_verified',
        'is_deactivated',
        'full_name',
        'is_incoming_invite',
        'is_outgoing_invite',
        'is_following',
        'is_follower',
        'is_connection',
        'is_verified',
        'avatar_url'
    ];
}
