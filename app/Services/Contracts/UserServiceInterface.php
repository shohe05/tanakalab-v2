<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validator($input);

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @return mixed
     */
    public function search();

    /**
     * @param $user
     * @return array
     */
    public function formatForShow($user);
}