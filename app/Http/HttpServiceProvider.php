<?php

namespace App\Http;

use Illuminate\Support\ServiceProvider;

final class HttpServiceProvider extends ServiceProvider
{
    public function register() {
        // $this->app->singleton(Middleware\CustomMiddleware::class);
    }
}

