<?php

namespace App\Sources\Auth\Contracts;

interface UserSourceInterface
{
    /**
     * GET request to the API show endpoint.
     *
     * @param int $id
     * @param array $query
     * @return mixed
     */
    public function show(int $id, array $query = []);

    /**
     * POST request to the API service search endpoint.
     *
     * @param array $payload
     * @return mixed
     */
    public function serviceSearch(array $payload);
}
