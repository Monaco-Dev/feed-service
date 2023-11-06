<?php

namespace App\Models\Support\Slug;

use Illuminate\Database\Eloquent\Builder;

trait Scopes
{
    /**
     * Get primary slug only.
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    public function scopePrimary(Builder $query)
    {
        return $query->where('is_primary', true)->first();
    }
}
