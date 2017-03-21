<?php

namespace NB\Tests\Utilities\Doctrine;

use Doctrine\DBAL\Types\Type;
use Illuminate\Config\Repository;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use NB\Utilities\Doctrine\DBALEnum;
use NB\Utilities\Doctrine\DBALServiceProvider;
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

    public function testLaravelConnection()
    {
        $app = $this->createApplication();

        $builder = $app['db']->getSchemaBuilder();
        $builder->dropIfExists('test');
        $builder->create('test', function ($table) {
            $table->increments('id');
            $table->string('text')->default('happy');
            $table->enum('sample', ['1', '2'])->default('1');
        });

        $columns = $app['db']->getDoctrineSchemaManager()->listTableColumns('test');

        $results = [
            [
                'integer',
                null,
            ],
            [
                'string',
                'happy',
            ],
            [
                'enum',
                '1',
            ],
        ];
        foreach ($columns as $column) {
            $arr = [$column->getType()->getName(), $column->getDefault()];
            $this->assertEquals(array_shift($results), $arr);
        }
    }

    private function createApplication()
    {
        $app = new Application(realpath(__DIR__.'/../'));

        $items = [
            'database' => ['default' => 'default'],
            'app' => ['providers' => [
                DBALServiceProvider::class,
            ]],
        ];
        array_set($items, 'database.connections.default', [
             'charset' => 'utf8',
             'collation' => 'utf8_unicode_ci',
             'prefix' => '',
             'driver' => 'mysql',
             'host' => '127.0.0.1',
             'database' => 'test',
             'username' => 'root',
             'password' => '',
        ]);
        $app->instance('config', new Repository($items));
        $app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });
        $app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
        $app->registerConfiguredProviders();

        return $app;
    }
}
