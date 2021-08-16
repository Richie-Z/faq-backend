<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = ['users', 'admin'])
    {
        // if ($this->auth->guard($guard)->guest()) {
        //     return response('Unauthorized.', 401);
        // }

        foreach ($guard as $g) {
            if ($this->auth->guard($g)->check()) {
                return $next($request);
            }
        }
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $th) {
            return $this->sendResponse('Token Expired', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $th) {
            return $this->sendResponse('Token Invalid', 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $th) {
            return $this->sendResponse('Token not provided', 401);
        }
        return $this->sendResponse('Unauthorized.', 401);
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
