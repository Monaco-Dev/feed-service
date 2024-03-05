<?php

namespace App\Sources\Contracts;

interface SourceInterface
{
    /**
     * Get the route for the API endpoint.
     * 
     * @return string
     */
    public function route();

    /**
     * Requires the bearer token in the request header.
     * 
     * @return string
     */
    public function token();

    /**
     * Requires the client id in the request header.
     * 
     * @return string
     */
    public function clientId();

    /**
     * Requires the client secret in the request header.
     * 
     * @return string
     */
    public function clientSecret();
}
