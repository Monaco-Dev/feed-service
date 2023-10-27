<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ShareRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\ShareRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        PostRepositoryInterface::class => PostRepository::class,
        ShareRepositoryInterface::class => ShareRepository::class,
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
