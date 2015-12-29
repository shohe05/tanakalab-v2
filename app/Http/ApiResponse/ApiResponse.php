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
     * @var array
     */
    protected $meta = [];

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($data)
    {
        return $this->jsonResponse('Success', $data);
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function created($data)
    {
        return $this->jsonResponse('Created', $data, 201);
    }

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationError($errors)
    {
        return $this->jsonResponse('Validation Error', ['errors' => $errors], 400);
    }

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unauthorized($errors)
    {
        if (!is_array($errors)) {
            $errors = [$errors];
        }
        return $this->jsonResponse('Unauthorized', ['errors' => $errors], 401);
    }

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notFound($errors)
    {
        if (!is_array($errors)) {
            $errors = [$errors];
        }
        return $this->jsonResponse('Not found', ['errors' => $errors], 404);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function internalServerError()
    {
        return $this->jsonResponse('Internal Server Error', [trans('api_response.v1.internal_server_error')], 500);
    }

    /**
     * @param $status
     * @param $data
     * @param int $httpStatusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jsonResponse($status, $data, $httpStatusCode = 200)
    {
        $this->setMeta([
            'status' => $status
        ]);
        return response()->json([
            'meta' => $this->getMeta(),
            'response' => $data
        ])->setStatusCode($httpStatusCode);
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }

    /**
     * @return array
     */
    protected function getMeta()
    {
        return $this->meta;
    }
}