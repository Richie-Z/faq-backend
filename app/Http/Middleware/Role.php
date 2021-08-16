<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (auth($role)->check()) {
            return $next($request);
        }
        return response()->json([
            'status' => false,
            'message' => "Sorry,Only $role allowed"
        ], 401);
    }
}
