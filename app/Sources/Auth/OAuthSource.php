<?php

namespace App\Sources\Auth;

use Illuminate\Support\Arr;

use App\Sources\Source;
use App\Sources\Auth\Contracts\OAuthSourceInterface;
use Facades\App\Helpers\Contracts\HttpRequestInterface as HttpRequest;

class OAuthSource extends Source implements OAuthSourceInterface
{
    /**
     * Create the source instance and declare the route endpoint.
     *
     */
    public function __construct()
    {
        $this->route = sprintf('%s/oauth/token', config('services.auth'));
    }

    /**
     * Request a token for the given credentials on /oauth/token passport
     * (Internal request)
     * 
     * @return object
     */
    public function getClientToken()
    {
        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.client_id'),
            'client_secret' => config('services.client_secret'),
            'scope' => '*'
        ];

        Arr::set($headers, 'Accept', 'application/json');

        return HttpRequest::post($this->route, $payload, $headers);
    }
}
