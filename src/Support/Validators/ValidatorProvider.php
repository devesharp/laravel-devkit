<?php

namespace Devesharp\Support\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorProvider extends ServiceProvider
{
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('numeric_string', function ($attribute, $value, $parameters) {
            if ($value === null) {
                return true;
            }

            return is_numeric($value);
        });
    }
}
