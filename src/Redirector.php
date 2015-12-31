<?php

namespace Waavi\SaveUrl;

use Illuminate\Config\Repository as Config;
use Illuminate\Routing\Redirector as LaravelRedirector;
use Illuminate\Routing\UrlGenerator;

class Redirector extends LaravelRedirector
{
    /**
     *  Session key for the saved url cache.
     *
     *  @var string
     */
    protected $sessionKey;

    public function __construct(UrlGenerator $generator, Config $config)
    {
        parent::__construct($generator);
        $this->sessionKey = $config->get('save-url.session-key');
    }

    /**
     * Create a new redirect response to the last saved url.
     *
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toSavedUrl($status = 302, $headers = [], $secure = null)
    {
        $path = $this->session->get($this->sessionKey, '/');
        return $this->to($path, $status, $headers, $secure);
    }
}
