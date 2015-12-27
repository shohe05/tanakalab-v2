<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\UserServiceInterface as User;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use Exception;
use Auth;

class UserController extends V1Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    public function __construct(User $user, ApiResponse $apiResponse)
    {
        $this->user = $user;
        $this->apiResponse = $apiResponse;
    }

    public function create(Request $request)
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

        return $this->apiResponse->success($user);
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

    public function delete(Request $request)
    {
        $id = $request->route('user');
        $this->user->delete($id);
        return $this->apiResponse->success(trans('api_response.v1.user.delete_success'));
    }

}