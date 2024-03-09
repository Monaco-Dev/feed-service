<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\TagServiceInterface;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        PostServiceInterface::class => PostService::class,
        TagServiceInterface::class => TagService::class,
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

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
