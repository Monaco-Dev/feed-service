<?php

namespace App\Sources\Auth\Contracts;

interface AuthSourceInterface
{
    /**
     * Retrieve authenticated user to the API auth endpoint.
     *
     * @param string $token
     * @return mixed
     */
    public function verifyToken(string $token);
}
