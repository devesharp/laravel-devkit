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

        Validator::extendImplicit('numeric_array', function ($attribute, $value, $parameters) {
            if ($value === null || $value == []) {
                return true;
            }

            if (!is_array($value)) {
                return false;
            }

            foreach ($value as $item) {
                if (!is_numeric($item)) {
                    return false;
                }
            }

            return true;
        });

        Validator::extendImplicit('color_hex', function ($attribute, $value, $parameters) {
            if ($value === null) {
                return true;
            }

            if (preg_match('/^#[a-f0-9]{6}$/i', $value)) {
                return true;
            }

            return false;
        });
    }
}
