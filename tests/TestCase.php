<?php

namespace Waavi\SaveUrl\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->setUpRoutes($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            //\Illuminate\Session\SessionServiceProvider::class,
            \Waavi\SaveUrl\SaveUrlServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });

        foreach (range(1, 10) as $index) {
            User::create(
                [
                    'name'     => "user{$index}",
                    'email'    => "user{$index}@spatie.be",
                    'password' => "password{$index}",
                ]
            );
        }
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpRoutes($app)
    {
        Route::any('/', function () {
            return 'home of ' . (auth()->check() ? auth()->user()->id : 'anonymous');
        });

        Route::any('login/{id}', ['middleware' => 'doNotSave', function ($id) {
            auth()->login(User::find($id));
            return redirect()->toSavedUrl();
        }]);

        Route::any('/save', function () {
            return 'Saved';
        });

        Route::any('/no-save', ['middleware' => 'doNotSave', function () {
            return 'No Save';
        }]);
    }

    protected function setUpSession($app)
    {
        $app['config']->set('session', [
            'driver'          => 'array',
            'lifetime'        => 120,
            'expire_on_close' => false,
            'encrypt'         => false,
            'lottery'         => [2, 100],
            'path'            => '/',
            'domain'          => 'localhost',
        ]);

        $kernel = app('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware('Illuminate\Session\Middleware\StartSession');
    }
}
