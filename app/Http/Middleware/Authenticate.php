<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;

class Authenticate
{
    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

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
        if (!(Auth::check() || Auth::viaRemember())) {
            return $this->apiResponse->unauthorized([trans('api_response.v1.please_login')]);
        }

        return $next($request);
    }
}
