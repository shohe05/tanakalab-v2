<?php

namespace App\Services\Contracts;

interface CommentServiceInterface
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
    public function create($user_id, $article_id, $input);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param $id
     * @return int
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);
}