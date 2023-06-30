<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'auth_mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'full_name',
        'email_verified_at',
        'is_email_verified',
        'phone_number_verified_at',
        'is_phone_number_verified',
        'socials',
        'broker_license',
        'mutuals_count',
        'connections_count',
        'pending_invitations_count',
        'request_invitations_count',
        'created_at'
    ];

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
        'phone_number_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_email_verified'
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
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function networkUsers()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connection_user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return User relationship.
     * 
     * @return App\Models\User
     */
    public function connectionUsers()
    {
        return $this->belongsToMany(User::class, 'connections', 'connection_user_id', 'user_id')
            ->whereHas('brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return Connection relationship.
     * 
     * @return App\Models\Connection
     */
    public function connections()
    {
        return $this->hasMany(Connection::class)
            ->whereHas('connection.brokerLicense', function ($query) {
                $query->whereNotNull('verified_at');
            });
    }

    /**
     * Return BrokerLicense relationship.
     * 
     * @return App\Models\BrokerLicense
     */
    public function brokerLicense()
    {
        return $this->hasOne(BrokerLicense::class);
    }

    /**
     * Return Post relationship.
     * 
     * @return App\Models\Post
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

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
    public function pins()
    {
        return $this->hasMany(Pin::class);
    }
}
