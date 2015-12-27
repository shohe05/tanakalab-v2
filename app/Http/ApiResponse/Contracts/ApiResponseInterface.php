<?php

namespace App\Http\ApiResponse\Contracts;

interface ApiResponseInterface
{
    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($data);

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationError($errors);

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function internalServerError();

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unauthorized($errors);

    /**
     * @param $code
     * @param $status
     * @param $data
     * @param int $httpStatusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jsonResponse($code, $status, $data, $httpStatusCode = 200);
}