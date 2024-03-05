<?php

namespace App\Models\Support\User;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait Attributes
{
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
    public function getIsVerifiedAttribute()
    {
        return (
            $this->email_verified_at &&
            !$this->deactivated_at &&
            !$this->deleted_at &&
            optional($this->license)->is_license_verified &&
            !optional($this->license)->is_license_expired
        );
    }

    /**
     * Hash password attribute.
     * 
     * @param string $value
     * @return mixed
     */
    public function setPasswordAttribute($value)
    {
        if (!Hash::info($value)['algo']) {
            $this->attributes['password'] = password_hash($value, PASSWORD_ARGON2I);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Append new attribute.
     * 
     * @return bool
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (Arr::has(parse_url($this->avatar), 'scheme')) {
                return $this->avatar;
            } else {
                return Storage::disk('gcs')->url($this->avatar);
            }
        } else {
            return null;
        }
    }
}
