<?php

namespace App\Providers;

use App\Services\Core\Validation\Rules;
use Illuminate\Support\ServiceProvider;
use Validator;

class ValidationRulesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidationRules();
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

    private function registerValidationRules()
    {
        $methods = get_class_methods(Rules::class);

        if ( ! empty($methods))
        {
            foreach ($methods as $rule)
            {
                Validator::extend($rule, Rules::class . '@' . $rule);
            }
        }
    }
}
