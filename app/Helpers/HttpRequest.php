<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

use App\Helpers\Contracts\HttpRequestInterface;

class HttpRequest implements HttpRequestInterface
{
    /**
     * HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Create the class instance and inject its dependency.
     *
     * @param GuzzleHttp\Client $http
     */
    public function __construct()
    {
        $this->http = new Client();
    }

    /**
     * HTTP GET request
     *
     * @param string $route
     * @param array|null $query
     * @param array $headers
     * @param bool $async
     * @return array
     */
    public function get(string $route, ?array $query, array $headers = [], bool $async = false)
    {
        if ($async) {
            return $this->http->requestAsync(
                'GET',
                $route,
                [
                    'http_errors' => false,
                    'query' => $query,
                    'headers' => $headers
                ]
            );
        }

        $response = $this->http->request(
            'GET',
            $route,
            [
                'http_errors' => false,
                'query' => $query,
                'headers' => $headers
            ]
        );

        switch ($response->getStatusCode()) {
            case 200:
                $response = json_decode($response->getBody());

                return $response;

            default:
                Log::error($response->getBody());
                abort($response->getStatusCode(), $response->getBody());
        }
    }

    /**
     * HTTP POST request
     *
     * @param string $route
     * @param array|null $payload
     * @param array $headers
     * @param bool $async
     * @return array
     */
    public function post(string $route, ?array $payload, array $headers = [], bool $async = false)
    {
        $options = [
            'http_errors' => false,
            'headers' => $headers
        ];

        switch (Arr::get($headers, 'Accept')) {
            case 'application/x-www-form-urlencoded':
                Arr::set($options, 'form_params', $payload);
                break;

            case 'application/json':
                Arr::set($options, 'json', $payload);
                break;

            case 'multipart/form-data':
                Arr::set($options, 'multipart', $payload);
                break;

            default:
                Arr::set($options, 'body', $payload);
                break;
        }

        if ($async) return $response = $this->http->requestAsync('POST', $route, $options);

        $response = $this->http->request('POST', $route, $options);

        switch ($response->getStatusCode()) {
            case 200:
            case 201:
                $response = json_decode($response->getBody());

                return $response;

            default:
                abort($response->getStatusCode(), $response->getBody());
        }
    }

    /**
     * HTTP PUT request
     *
     * @param string $route
     * @param array $payload
     * @param array $headers
     * @param bool $async
     * @return array
     */
    public function put(string $route, array $payload, array $headers = [], bool $async = false)
    {
        $options = [
            'http_errors' => false,
            'headers' => $headers
        ];

        switch (Arr::get($headers, 'Accept')) {
            case 'application/x-www-form-urlencoded':
                Arr::set($options, 'form_params', $payload);
                break;

            case 'application/json':
                Arr::set($options, 'json', $payload);
                break;

            case 'multipart/form-data':
                Arr::set($options, 'multipart', $payload);
                break;

            default:
                Arr::set($options, 'body', $payload);
                break;
        }

        if ($async) return $response = $this->http->requestAsync('PUT', $route, $options);

        $response = $this->http->request('PUT', $route, $options);

        switch ($response->getStatusCode()) {
            case 200:
                $response = json_decode($response->getBody());

                return $response;

            default:
                abort($response->getStatusCode(), $response->getBody());
        }
    }

    /**
     * HTTP DELETE request
     *
     * @param string $route
     * @param array $headers
     * @param bool $async
     * @return array
     */
    public function delete(string $route, ?array $query,  array $headers = [], bool $async = false)
    {
        if ($async) {
            return $this->http->requestAsync(
                'DELETE',
                $route,
                [
                    'http_errors' => false,
                    'headers' => $headers,
                    'query' => $query,
                ]
            );
        }

        $response = $this->http->request(
            'DELETE',
            $route,
            [
                'http_errors' => false,
                'headers' => $headers,
                'query' => $query,
            ]
        );

        switch ($response->getStatusCode()) {
            case 200:
                $response = json_decode($response->getBody());

                return $response;

            default:
                abort($response->getStatusCode(), $response->getBody());
        }
    }
}
