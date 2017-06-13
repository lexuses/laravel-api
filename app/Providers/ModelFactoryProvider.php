<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Support\ServiceProvider;

class ModelFactoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        FactoryBuilder::macro('firstOrCreate', function () {
            return function () {
                // $this refers to Illuminate\Database\Eloquent\FactoryBuilder instance.

                return $this->class::first()->id ?? factory($this->class)->create()->id;
            };
        });
    }
}
