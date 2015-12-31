<?php

namespace Waavi\SaveUrl;

use Closure;
use Illuminate\Http\Request;

class DoNotSaveUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->add(['save-url.do-not-save' => true]);

        return $next($request);
    }
}
