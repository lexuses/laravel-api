<?php

namespace App\Providers;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Resource\Collection as FractalCollection;

class CollectionMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('transformer', function ($transformer, $callback = null) {

            $manager = new FractalManager();

            if($callback instanceof Closure)
            {
                call_user_func($callback, $manager);
            }

            $data = new FractalCollection($this, $transformer);
            $resource = $manager->createData($data)->toArray();

            return new $this($resource);

        });

        Collection::macro('transformerEach', function ($transformer, $callback = null) {

            return new $this(
                $this->mapWithKeys(function ($items, $key) use ($transformer, $callback){
                    return [$key => $items->transformer($transformer, $callback )];
                })
            );

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
