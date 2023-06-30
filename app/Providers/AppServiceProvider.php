<?php

namespace App\Providers;

use App\Services\Contracts\PinServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\ShareServiceInterface;
use App\Services\PinService;
use App\Services\PostService;
use App\Services\ShareService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        PostServiceInterface::class => PostService::class,
        PinServiceInterface::class => PinService::class,
        ShareServiceInterface::class => ShareService::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
