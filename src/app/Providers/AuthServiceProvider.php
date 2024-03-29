<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('update-post', [PostPolicy::class, 'update']);
        Gate::define('delete-post', [PostPolicy::class, 'delete']);
        Gate::define('restore-post', [PostPolicy::class, 'restore']);
        Gate::define('pin-post', [PostPolicy::class, 'pin']);
        Gate::define('unpin-post', [PostPolicy::class, 'unpin']);
        Gate::define('share-post', [PostPolicy::class, 'share']);
        Gate::define('search-wall-post', [PostPolicy::class, 'searchWall']);
        Gate::define('search-matches-post', [PostPolicy::class, 'searchMatches']);
    }
}
