<?php

namespace App\Sources\Auth;

use Illuminate\Support\Arr;

use App\Sources\Source;
use App\Sources\Auth\Contracts\AuthSourceInterface;
use Facades\App\Sources\Auth\Contracts\OAuthSourceInterface as OAuthSource;
use Facades\App\Helpers\Contracts\HttpRequestInterface as HttpRequest;

class AuthSource extends Source implements AuthSourceInterface
{
    /**
     * Create the source instance and declare the route endpoint.
     *
     */
    public function __construct()
    {
        $this->route = sprintf('%s/api/service', config('services.auth'));
        $this->token = OAuthSource::getClientToken();
    }

    /**
     * Retrieve authenticated user to the API auth endpoint.
     *
     * @param string $token
     * @return mixed
     */
    public function verifyToken(string $token)
    {
        $route = sprintf('%s/verify-token', $this->route);

        $payload = [
            'bearerToken' => $token
        ];

        Arr::set($headers, 'Accept', 'application/json');
        Arr::set($headers, 'Authorization', sprintf('Bearer %s', optional($this->token)->access_token));

        return HttpRequest::post($route, $payload, $headers);
    }
}
