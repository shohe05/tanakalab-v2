<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\UserServiceInterface as User;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use Exception;

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

    /**
     * UserController constructor.
     * @param User $user
     * @param ApiResponse $apiResponse
     */
    public function __construct(User $user, ApiResponse $apiResponse)
    {
        $this->user = $user;
        $this->apiResponse = $apiResponse;
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request)
    {
        try {
            $user = $this->user->find($request->route('user'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.user.not_found'));
        }

        $user = $this->user->formatForShow($user);
        return $this->apiResponse->success($user);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request)
    {
        $id = $request->route('user');
        try {
            $this->user->delete($id);
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.user.not_found'));
        }
        return $this->apiResponse->success(trans('api_response.v1.user.delete_success'));
    }

}