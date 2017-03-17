<?php

namespace NB\Utilities\Doctrine;

use Illuminate\Support\ServiceProvider;

class DBALServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dbalhelper', function ($app) {
            return new DBALHelper();
        });
    }
}
