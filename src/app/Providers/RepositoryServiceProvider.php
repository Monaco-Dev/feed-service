<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        PostRepositoryInterface::class => PostRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
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
