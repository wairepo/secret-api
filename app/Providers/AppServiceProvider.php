<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Validator::extend('json_keys', function($attribute, $json, $parameters, $validator) {

            if( isset($json[0]) ) {
                foreach ($json as $key => $value) {

                    if( strlen(preg_replace("/[^a-zA-Z]+/", "", array_keys($value)[0])) > 111 ) {
                        return false;
                    }
                }
            }

        });
    }
}
