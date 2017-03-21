<?php

namespace NB\Utilities\Doctrine;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\ServiceProvider;

class DBALServiceProvider extends ServiceProvider
{
    public function register()
    {
        Type::addType('enum', DBALEnum::class);
        $this->app['db']->connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enum');
    }
}
