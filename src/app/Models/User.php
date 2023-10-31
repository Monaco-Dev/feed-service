<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'url',
        'is_incoming_invite',
        'is_outgoing_invite',
        'is_following',
        'is_follower',
        'is_connection',
    ];

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsEmailVerifiedAttribute()
    {
        return !!$this->email_verified_at;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsDeactivatedAttribute()
    {
        return !!$this->deactivated_at;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsOutgoingInviteAttribute()
    {
        if (!optional(request()->user())->id) return false;

        return $this->incomingInvites()
            ->wherePivot(
                'user_id',
                request()->user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsIncomingInviteAttribute()
    {
        if (!optional(request()->user())->id) return false;

        return $this->outgoingInvites()
            ->wherePivot(
                'connection_invitation_user_id',
                request()->user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsFollowingAttribute()
    {
        if (!optional(request()->user())->id) return false;

        return $this->followers()
            ->wherePivot(
                'user_id',
                request()->user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsFollowerAttribute()
    {
        if (!optional(request()->user())->id) return false;

        return $this->following()
            ->wherePivot(
                'follow_user_id',
                request()->user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsConnectionAttribute()
    {
        if (!optional(request()->user())->id) return false;

        return $this->connections()
            ->wherePivot(
                'connection_user_id',
                request()->user()->id
            )->exists();
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getUrlAttribute()
    {
        $slug = optional(optional($this->slugs())->primary())->slug;

        return $slug ? "/profile/$slug" : null;
    }

    /**
     * Hash password attribute.
     * 
     * @param string $value
     * @return mixed
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_ARGON2I);
    }

    /**
     * Return BrokerLicense relationship.
     * 
     * @return App\Models\BrokerLicense
     */
    public function brokerLicense()
    {
        return $this->setConnection('auth_mysql')->hasOne(BrokerLicense::class);
    }

    /**
     * Return Slug relationship.
     * 
     * @return App\Models\Slug
     */
    public function slugs()
    {
        return $this->setConnection('auth_mysql')->hasMany(Slug::class);
    }

    /**
     * Return Post relationship.
     * 
     * @return App\Models\Post
     */
    public function posts()
    {
        return $this->setConnection('mysql')->hasMany(Post::class);
    }

    /**
     * Return Share relationship.
     * 
     * @return App\Models\Share
     */
    public function shares()
    {
        return $this->setConnection('mysql')
            ->belongsToMany(Post::class, 'shares')
            ->withTimestamps();
    }

    /**
     * Return Pin relationship.
     * 
     * @return App\Models\Pin
     */
    public function pins()
    {
        return $this->setConnection('mysql')
            ->belongsToMany(Post::class, 'pins')
            ->withTimestamps();
    }

    /**
     * Return Connections relationship.
     * 
     * @return App\Models\Connection
     */
    public function connections()
    {
        return $this->setConnection('auth_mysql')
            ->belongsToMany(
                User::class,
                'connections',
                'user_id',
                'connection_user_id'
            )
            ->withTimestamps();
    }

    /**
     * Return Connection Invitations relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function outgoingInvites()
    {
        return $this->setConnection('auth_mysql')
            ->belongsToMany(
                User::class,
                'connection_invitations',
                'user_id',
                'connection_invitation_user_id'
            )
            ->withTimestamps();
    }

    /**
     * Return Connection Invitations relationship.
     * 
     * @return App\Models\ConnectionInvitation
     */
    public function incomingInvites()
    {
        return $this->setConnection('auth_mysql')
            ->belongsToMany(
                User::class,
                'connection_invitations',
                'connection_invitation_user_id',
                'user_id'
            )
            ->withTimestamps();
    }

    /**
     * Return Follow relationship.
     * 
     * @return App\Models\Follow
     */
    public function following()
    {
        return $this->setConnection('auth_mysql')
            ->belongsToMany(
                User::class,
                'follows',
                'user_id',
                'follow_user_id',
            )
            ->withTimestamps();
    }

    /**
     * Return Follow relationship.
     * 
     * @return App\Models\Follow
     */
    public function followers()
    {
        return $this->setConnection('auth_mysql')
            ->belongsToMany(
                User::class,
                'follows',
                'follow_user_id',
                'user_id',
            )
            ->withTimestamps();
    }

    /**
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        // $brokerLicenseModel = (new BrokerLicense)->getConnectionName();
        // $authDb = config("database.connections.$brokerLicenseModel.database");

        return $query->whereNotNull('email_verified_at')
            ->whereNull('deactivated_at')
            ->whereNull('deleted_at');
        // ->whereHas('brokerLicense', function ($query) use ($authDb) {
        //     $query->from("$authDb.broker_licenses")->verified();
        // });
    }
}
