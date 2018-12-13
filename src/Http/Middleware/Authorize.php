<?php

namespace DigitalCloud\PageTool\Http\Middleware;

use DigitalCloud\PageTool\PageTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(PageTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
