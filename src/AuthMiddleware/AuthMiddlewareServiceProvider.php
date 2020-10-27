<?php

namespace Cego\AuthMiddleware;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;

class AuthMiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        // Publish resource to the project consuming this package
        $this->publishes([
            __DIR__ . '/../../publishable/config/auth-middleware.php' => config_path('auth-middleware.php'),
        ]);

        // Push Middleware to global middleware stack
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(RemoteUserAuthentication::class);
    }
}
