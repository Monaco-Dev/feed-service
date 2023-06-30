<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Helpers\Contracts\HttpRequestInterface;
use App\Helpers\HttpRequest;
use App\Sources\Auth\Contracts\AuthSourceInterface;
use App\Sources\Auth\AuthSource;
use App\Sources\Auth\Contracts\OAuthSourceInterface;
use App\Sources\Auth\OAuthSource;

class SourceServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        HttpRequestInterface::class => HttpRequest::class,
        AuthSourceInterface::class => AuthSource::class,
        OAuthSourceInterface::class => OAuthSource::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
