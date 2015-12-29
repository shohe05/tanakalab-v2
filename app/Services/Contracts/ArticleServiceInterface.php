<?php

namespace App\Services\Contracts;

interface ArticleServiceInterface
{
    /**
     * @param $request
     * @param null $perPage
     * @param int $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function get($request, $perPage = null, $page = 1);

    /**
     * @param \App\Models\Article[] $articles
     * @return array
     */
    public function formatForIndex($articles);

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validator($input);

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validatorForUpdate($input);

    /**
     * @param $article_input
     * @param $tag_input
     * @return mixed
     */
    public function create($user, $article_input, $tag_input);

    /**
     * @param $id
     * @param $article_input
     * @param $tag_input
     * @return mixed
     */
    public function update($id, $article_input, $tag_input);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $article
     * @return array
     */
    public function formatForShow($article);

    /**
     * @param $article
     * @return array
     */
    public function formatForCreate($article);

    /**
     * @param $id
     * @return int
     */
    public function delete($id);

    /**
     * @param $id
     * @param $user_id
     * @return mixed
     */
    public function clip($id, $user_id);

    /**
     * @param $id
     * @param $user_id
     */
    public function unclip($id, $user_id);

    /**
     * @param $request
     * @param int $page
     * @param null $perPage
     * @return mixed
     */
    public function search($request, $page = 1, $perPage = null);
}