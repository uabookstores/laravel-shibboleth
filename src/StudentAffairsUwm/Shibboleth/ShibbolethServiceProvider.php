<?php

namespace StudentAffairsUwm\Shibboleth;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ShibbolethServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/shibboleth.php' => config_path('shibboleth.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../../routes/shibboleth.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (config('shibboleth.jwtauth')) {
            $this->app->register('Tymon\JWTAuth\Providers\LaravelServiceProvider');
            $loader = AliasLoader::getInstance();
            $loader->alias('JWTAuth', 'Tymon\JWTAuth\Facades\JWTAuth');
            $loader->alias('JWTFactory', 'Tymon\JWTAuth\Facades\JWTFactory');
        }

        $this->app['auth']->provider('shibboleth', function ($app) {
            return new Providers\ShibbolethUserProvider($app['config']['auth.providers.users.model']);
        });
    }
}
