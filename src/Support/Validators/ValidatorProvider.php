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
        }, 'O campo :attribute deve ser um número');

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

        Validator::extendImplicit('CPF_CNPJ', function ($attribute, $value, $parameters) {
            $c = preg_replace('/\D/', '', $value);

            if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
                return false;
            }

            for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

            if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }

            for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

            if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }

            return true;
        });
    }
}
