<?php

namespace Shops\Providers;

use Illuminate\Support\ServiceProvider;

class ShopsServiceProvider extends ServiceProvider
{
    public function register()
    {
    }


    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        //$this->loadTranslationsFrom(base_path() . '/lang/ru', 'shops');
    }
}
