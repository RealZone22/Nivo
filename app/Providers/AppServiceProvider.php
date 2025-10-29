<?php

namespace App\Providers;

use App\Socialite\CustomOAuthProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{
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
        if (! app()->runningInConsole()) {
            $socialite = $this->app->make(Factory::class);

            $socialite->extend('custom', function () use ($socialite) {
                return $socialite->buildProvider(CustomOAuthProvider::class, [
                    'client_id' => settings('auth.oauth.client_id', config('settings.auth.oauth.client_id')),
                    'client_secret' => settings('auth.oauth.client_secret', config('settings.auth.oauth.client_secret')),
                    'redirect' => settings('auth.oauth.redirect_uri', config('settings.auth.oauth.redirect_uri')),
                ]);
            });
        }
    }
}
