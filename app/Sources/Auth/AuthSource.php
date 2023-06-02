<?php

namespace App\Sources\Auth;

use Illuminate\Support\Arr;

use App\Sources\Source;
use App\Sources\Auth\Contracts\AuthSourceInterface;
use Facades\App\Helpers\Contracts\HttpRequestInterface as HttpRequest;

class AuthSource extends Source implements AuthSourceInterface
{
    /**
     * Create the source instance and declare the route endpoint.
     *
     */
    public function __construct()
    {
        $this->route = sprintf('%s/api/auth', config('services.auth'));
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

        Arr::set($headers, 'Accept', 'application/json');
        Arr::set($headers, 'Authorization', sprintf('Bearer %s', $token));

        return HttpRequest::get($route, [], $headers);
    }
}
