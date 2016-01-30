<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use Auth;
use Illuminate\Http\Request;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use App\Services\Contracts\UserServiceInterface as User;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends V1Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse, User $user)
    {
        $this->apiResponse = $apiResponse;
        $this->user = $user;
    }

    public function register(Request $request)
    {
        // Validation
        $validator = $this->user->validator($request->all());
        if ($validator->fails()) {
            return $this->apiResponse->validationError(array_flatten($validator->errors()->toArray()));
        }

        // Create
        try {
            $user = $this->user->create($request->all());
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->created($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->apiResponse->unauthorized(trans('api_response.v1.user.login_failed'));
            }
        } catch (JWTException $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success(compact('token'));
    }

    public function check(Request $request, \Tymon\JWTAuth\JWTAuth $auth)
    {
        if (!$this->isLogin($request, $auth)) {
            return $this->apiResponse->unauthorized([]);
        }

        return $this->apiResponse->success([]);
    }

    private function isLogin(Request $request, \Tymon\JWTAuth\JWTAuth $auth)
    {
        if (! $token = $auth->setRequest($request)->getToken()) {
            return false;
        }

        try {
            $user = $auth->authenticate($token);
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

    public function logout()
    {
        Auth::logout();
        return $this->apiResponse->success(trans('api_response.v1.user.logout_success'));
    }
}