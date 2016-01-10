<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class CheckJwtLoging extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$this->check($request)) {
            return view('front.login');
        }

        return $next($request);
    }

    public function check($request)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return false;
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return false;
        } catch (JWTException $e) {
            return false;
        }

        if (! $user) {
            return false;
        }

        return true;
    }
}
