<?php

namespace App\Models\Support\User;

use Illuminate\Database\Eloquent\Builder;

use App\Models\License;

trait Scopes
{
    /**
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        $licenseModel = (new License())->getConnectionName();
        $authDb = config("database.connections.$licenseModel.database");

        return $query->whereNotNull('email_verified_at')
            ->whereNull('deactivated_at')
            ->whereNull('users.deleted_at')
            ->whereHas('license', function ($query) use ($authDb) {
                $query->from("$authDb.licenses")->verified();
            });
    }
}
