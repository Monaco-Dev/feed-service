<?php

namespace App\Models\Support\User;

use Illuminate\Database\Eloquent\Builder;

trait Scopes
{
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
