<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware
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
        return $next($request);
    }
    public function sendResponse($message = null, $code)
    {
        $status = $code == 200 ? true : false;
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $code);
    }
}
