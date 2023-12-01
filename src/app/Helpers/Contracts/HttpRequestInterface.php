<?php

namespace App\Helpers\Contracts;

interface HttpRequestInterface
{
    /**
     * HTTP GET request
     * 
     * @param string $route
     * @param array|null $query
     * @param array $headers
     * @param bool $async
     * @return mixed
     */
    public function get(string $route, ?array $query, array $headers = [], bool $async = false);

    /**
     * HTTP POST request
     * 
     * @param string $route
     * @param array|null $payload
     * @param array $headers
     * @param bool $async
     * @return mixed
     */
    public function post(string $route, ?array $payload, array $headers = [], bool $async = false);

    /**
     * HTTP PUT request
     * 
     * @param string $route
     * @param array $payload
     * @param array $headers
     * @param bool $async
     * @return mixed
     */
    public function put(string $route, array $payload, array $headers = [], bool $async = false);

    /**
     * HTTP DELETE request
     * 
     * @param string $route
     * @param array $headers
     * @param bool $async
     * @return mixed
     */
    public function delete(string $route, ?array $query, array $headers = [], bool $async = false);
}
