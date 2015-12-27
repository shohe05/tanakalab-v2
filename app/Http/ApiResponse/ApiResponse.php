<?php

namespace App\Http\ApiResponse;

use App\Http\ApiResponse\Contracts\ApiResponseInterface;

/**
 * Class ApiResponseAbstract
 * @package App\Http\ApiResponse\Response\Contracts
 */
class ApiResponse implements ApiResponseInterface
{

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($data)
    {
        return $this->jsonResponse(2000, 'Success', $data);
    }

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationError($errors)
    {
        return $this->jsonResponse(4000, 'Validation Error', ['errors' => $errors], 400);
    }

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unauthorized($errors)
    {
        return $this->jsonResponse(4001, 'Unauthorized', ['errors' => $errors], 401);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function internalServerError()
    {
        return $this->jsonResponse(5000, 'Internal Server Error', ['Please contact developer.'], 500);
    }

    /**
     * @param $code
     * @param $status
     * @param $data
     * @param int $httpStatusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jsonResponse($code, $status, $data, $httpStatusCode = 200)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => $status
            ],
            'response' => $data
        ])->setStatusCode($httpStatusCode);
    }
}