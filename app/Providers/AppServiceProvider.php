<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * List of Local Enviroment Providers
     * @var array
     */
    protected $localProviders = [
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //if mysql version < 5.7.7
        Schema::defaultStringLength(191);

        if ($this->app->isLocal())
        {
            $this->registerServiceProviders();
        }
        else{
            DB::connection()->disableQueryLog();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Load local service providers
     */
    protected function registerServiceProviders()
    {
        foreach ($this->localProviders as $provider)
        {
            $this->app->register($provider);
        }
    }
}
