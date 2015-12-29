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
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function created($data);

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
    public function notFound($errors);

    /**
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unauthorized($errors);

    /**
     * @param $status
     * @param $data
     * @param int $httpStatusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jsonResponse($status, $data, $httpStatusCode = 200);

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta);
}