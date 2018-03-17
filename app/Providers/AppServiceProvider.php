<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\App\Validation\ValidationServiceProvider::class);
        $this->app->register(\App\Http\HttpServiceProvider::class);
        $this->app->register(\App\Model\ModelServiceProvider::class);

        $this->doctrine();
    }

    private function doctrine() {
        $this->app->singleton(DBAL\Connection::class, function($app) {
            return $app->make('em')->getConnection();
        });
        $this->app->alias('em', 'Doctrine\Common\Persistence\ObjectManager');
    }
}
