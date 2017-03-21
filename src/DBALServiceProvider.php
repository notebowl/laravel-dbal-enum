<?php

namespace NB\Utilities\Doctrine;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DBALServiceProvider extends ServiceProvider
{
    public function register()
    {
        Type::addType('enum', DBALEnum::class);
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enum');
    }
}
