<?php

namespace App\Models\Support\Post;

use Illuminate\Support\Arr;

trait Attributes
{
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
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsSharedAttribute()
    {
        return Arr::has($this->content, 'id');
    }
}
