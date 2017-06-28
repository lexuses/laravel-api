<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class ServerRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Request::ip() != gethostbyname(gethostname()) AND Request::ip() != '127.0.0.1' )
            throw new ServerRequestFailedException();

        return $next($request);
    }
}
