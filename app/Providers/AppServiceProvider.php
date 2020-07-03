<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \App::setLocale('fr');

        \View::share('channels', \App\Channel::orderBy('name', 'asc')->get());

        // \Validator::extend('spamfree', 'App\Rules\SpamFree@passes'); // -> Voir app/rule cette méthode était pour version <= 5.4 de larvel
    }
}
