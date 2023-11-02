<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function view(User $user, Post $post): bool
    {
        return $post->is_verified;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can pin the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function pin(User $user, Post $post): bool
    {
        return $post->is_verified && !$user->pins()->where('post_id', $post->id)->exists();
    }

    /**
     * Determine whether the user can unpin the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function unpin(User $user, Post $post): bool
    {
        return $post->is_verified && $user->pins()->where('post_id', $post->id)->exists();
    }

    /**
     * Determine whether the user can share the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\Post $post
     * @return bool
     */
    public function share(User $user, Post $post): bool
    {
        return $post->is_verified;
    }

    /**
     * Determine whether the user can search the model.
     * 
     * @param App\Models\User $user
     * @param App\Models\User $model
     * @return bool
     */
    public function searchWall(User $user, User $model): bool
    {
        return $model->is_verified;
    }
}
