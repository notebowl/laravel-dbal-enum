<?php

namespace NB\Tests\Utilities\Doctrine;

use NB\Utilities\Doctrine\DBALEnum;
use Doctrine\DBAL\Types\Type;
use PHPUnit_Framework_TestCase;

class DBALServiceTest extends PHPUnit_Framework_TestCase
{
    public function testEnumExists()
    {
        Type::addType('enum', DBALEnum::class);
        $man = new \Doctrine\DBAL\Platforms\MySqlPlatform();
        $man->registerDoctrineTypeMapping('enum', 'enum');
        $this->assertEquals($man->getDoctrineTypeMapping('enum'), 'enum');
    }
}
