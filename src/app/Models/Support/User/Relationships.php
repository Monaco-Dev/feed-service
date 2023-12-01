<?php

namespace App\Models\Support\User;

use App\Models\BrokerLicense;
use App\Models\Post;
use App\Models\User;

trait Relationships
{
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
}
