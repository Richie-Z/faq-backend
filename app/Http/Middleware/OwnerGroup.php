<?php

namespace App\Http\Middleware;

use Closure;

class OwnerGroup
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
        $user = auth()->user()->group()->select('id')->get()->pluck('id')->toArray();
        $param = request('id');
        if (in_array($param, $user)) {
            return $next($request);
        }
        return response()->json([
            'status' => false,
            'message' => "Sorry,Only Group Owner allowed"
        ], 401);
    }
}
