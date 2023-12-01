<?php

namespace App\Sources\Auth\Contracts;

interface OAuthSourceInterface
{
    /**
     * Request a token for the given credentials on /oauth/token passport
     * (Internal request)
     * 
     * @return object
     */
    public function getClientToken();
}
