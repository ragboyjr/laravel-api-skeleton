<?php

namespace App\Validation;

use Krak\Validation;
use Krak\Cargo;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register() {
        $this->app->singleton(Validation\Kernel::class, function($app) {
            $container = new Cargo\Container\PsrDelegateContainer(
                Cargo\container(),
                $app
            );
            $validation = new Validation\Kernel($container);
            $validation->withDoctrineValidators();
            $validation->context([
                'doctrine.model_prefix' => \App\Model::class,
            ]);
            $validation->validators([

            ]);
            return $validation;
        });
        $this->app->alias(Validation\Kernel::class, 'validation');
    }
}

