<?php

namespace App\System\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApi
{
    /**
     * Forces the request to be handled as JSON by default
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accept = $request->header('Accept');

        if (empty($accept) || $accept === '*/*') {
            $request->headers->set('Accept', 'application/json');
        }

        {
            return $next($request);
        }
    }
}
