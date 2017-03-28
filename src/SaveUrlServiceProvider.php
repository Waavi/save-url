<?php

namespace Waavi\SaveUrl;

use Illuminate\Support\ServiceProvider;

class SaveUrlServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/save-url.php' => config_path('save-url.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../config/save-url.php', 'save-url'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRedirector();
        $this->registerMiddleware();
    }

    /**
     * Register the Redirector service.
     *
     * @return void
     */
    protected function registerRedirector()
    {
        $this->app->singleton('redirect', function ($app) {
            $redirector = new Redirector($app['url'], $app['config']);
            if (isset($app['session.store'])) {
                $redirector->setSession($app['session.store']);
            }
            return $redirector;
        });
    }

    /**
     *  Register middleware
     *
     *  @return void
     */
    public function registerMiddleware()
    {
        $this->app[\Illuminate\Routing\Router::class]->middleware('doNotSave', DoNotSaveUrlMiddleware::class);
        $this->app[\Illuminate\Contracts\Http\Kernel::class]->pushMiddleware(SaveUrlMiddleware::class);
    }
}
