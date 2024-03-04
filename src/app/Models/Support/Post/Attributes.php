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
            optional($this->user)->email_verified_at &&
            !optional($this->user)->deactivated_at &&
            !optional($this->user)->deleted_at &&
            optional(optional($this->user)->license)->is_license_verified &&
            !optional(optional($this->user)->license)->is_license_expired
        );
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getIsSharedAttribute()
    {
        return Arr::has($this->content, 'post_id');
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
