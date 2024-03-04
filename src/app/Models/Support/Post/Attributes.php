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
        return (
            $this->user->email_verified_at &&
            !$this->user->deactivated_at &&
            !$this->user->deleted_at &&
            $this->user->license->is_license_verified &&
            !$this->user->license->is_license_expired
        );
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

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsEditedAttribute()
    {
        return $this->created_at != $this->updated_at;
    }
}
