<?php

namespace Waavi\SaveUrl;

use Closure;
use Illuminate\Foundation\Application;

class SaveUrlMiddleware
{
    /**
     *  Session Store
     *
     *  @var \Illuminate\Session\Store
     */
    protected $store;

    /**
     *  Session key for saved urls.
     *
     *  @var string
     */
    protected $sessionKey;

    /**
     *  True if the application is running in console outside testing environment
     *
     *  @var boolean
     */
    protected $isRunningInConsole;

    /**
     *  Create a new SaveUrlMiddleware instance
     *
     *  @param  Store   $store
     *  @param  Config  $config
     *  @return void
     */
    public function __construct(Application $app)
    {
        $this->store              = $app['session.store'];
        $this->sessionKey         = $app['config']->get('save-url.session-key');
        $this->isRunningInConsole = !$app->environment('testing') && $app->runningInConsole();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->isCacheable($request)) {
            $uri = $request->getUri();
            $this->store->put($this->sessionKey, $uri);
        }
        return $response;
    }

    /**
     *  Check if the given request is cacheable
     *
     *  @param  \Illuminate\Http\Request  $request
     *  @return boolean
     */
    protected function isCacheable($request)
    {
        return !$this->isRunningInConsole && !$request->attributes->has('save-url.do-not-save') && is_null($request->user()) && $request->isMethod('GET') && !$request->ajax();
    }
}
