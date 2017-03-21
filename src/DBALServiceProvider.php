<?php

namespace NB\Utilities\Doctrine;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\ServiceProvider;

class DBALServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (Type::hasType('enum')) {
            Type::overrideType('enum', DBALEnum::class);
        } else {
            Type::addType('enum', DBALEnum::class);
        }
        $this->app['db']->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enum');
    }
}
