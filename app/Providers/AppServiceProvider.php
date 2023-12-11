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
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      setlocale(LC_TIME, config('app.locale'));
      \Carbon\Carbon::setLocale('es');

      app()->bind('path.public', function(){
        return env('PUBLIC_PATH', base_path() . '/public');
      });
    }
}
