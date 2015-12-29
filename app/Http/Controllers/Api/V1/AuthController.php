<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use Auth;
use Illuminate\Http\Request;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use App\Services\Contracts\UserServiceInterface as User;
use Exception;

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
            return $this->apiResponse->validationError($validator->errors());
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
        if (Auth::viaRemember() || Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], true)) {
            return $this->apiResponse->success(Auth::user());
        }

        return $this->apiResponse->unauthorized(trans('api_response.v1.user.login_failed'));
    }

    public function logout()
    {
        Auth::logout();
        return $this->apiResponse->success(trans('api_response.v1.user.logout_success'));
    }
}