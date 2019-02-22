<?php

namespace Optimus\Media\Tests;

use Optimus\Users\Models\AdminUser;
use Optimus\Users\UserServiceProvider;
use Optimus\Media\MediaServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(
            __DIR__ . '/../database/factories'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            UserServiceProvider::class,
            \Optix\Media\MediaServiceProvider::class,
            MediaServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }

    protected function signIn()
    {
        $user = AdminUser::create([
            'name' => 'Admin',
            'email' => 'admin@optimuscms.com',
            'username' => 'admin',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($user, 'admin');

        return $user;
    }

    protected function expectedMediaJsonStructure()
    {
        return [
            'id',
            'folder_id',
            'name',
            'file_name',
            'mime_type',
            'size',
            'created_at',
            'updated_at'
        ];
    }

    protected function expectedFolderJsonStructure()
    {
        return [
            'id',
            'name',
            'parent_id',
            'created_at',
            'updated_at'
        ];
    }
}
