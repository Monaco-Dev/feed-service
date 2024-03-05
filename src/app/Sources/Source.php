<?php

namespace App\Sources;

use App\Sources\Contracts\SourceInterface;

abstract class Source implements SourceInterface
{
    /**
     * The route for the API.
     * 
     * @var string
     */
    protected $route;

    /**
     * Attach the bearer token in the request header.
     * 
     * @var string
     */
    protected $token;

    /**
     * Attach the client id in the request header.
     * 
     * @var string
     */
    protected $clientId;

    /**
     * Attach the client secret in the request header.
     * 
     * @var string
     */
    protected $clientSecret;

    /**
     * Create the class instance and inject its dependency.
     * 
     * @param String $route
     */
    public function __construct(string $route)
    {
        $this->route = $route;
    }

    /**
     * Get the route for the API endpoint.
     * 
     * @return string
     */
    public function route()
    {
        return $this->route;
    }

    /**
     * Requires the bearer token in the request header.
     * 
     * @return string
     */
    public function token()
    {
        return $this->token;
    }

    /**
     * Requires the client id in the request header.
     * 
     * @return string
     */
    public function clientId()
    {
        return $this->clientId;
    }

    /**
     * Requires the client secret in the request header.
     * 
     * @return string
     */
    public function clientSecret()
    {
        return $this->clientSecret;
    }
}
