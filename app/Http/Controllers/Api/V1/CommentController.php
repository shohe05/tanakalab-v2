<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use App\Services\Contracts\CommentServiceInterface as Comment;
use Illuminate\Http\Request;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use Exception;

class CommentController extends V1Controller
{
    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * CommentController constructor.
     * @param Comment $comment
     * @param ApiResponse $apiResponse
     */
    public function __construct(Comment $comment, ApiResponse $apiResponse)
    {
        $this->comment = $comment;
        $this->apiResponse = $apiResponse;
        $this->middleware('jwt.auth');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        // Validation
        $validator = $this->comment->validator($request->all());
        if ($validator->fails()) {
            return $this->apiResponse->validationError(array_flatten($validator->errors()->toArray()));
        }

        // Create
        try {
            $user = $request->user();
            $comment = $this->comment->create($user->id, $request->route('article'), $request->all());
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }
        return $this->apiResponse->created([
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user->name,
            'body' => $comment->body,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        try {
            $comment = $this->comment->find($request->route('comment'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.comment.not_found'));
        }

        // Authorize
        if ($user->id != $comment->user->id) {
            return $this->apiResponse->unauthorized(trans('api_response.v1.comment.no_permission'));
        }

        // Validation
        $validator = $this->comment->validator($request->all());
        if ($validator->fails()) {
            return $this->apiResponse->validationError($validator->errors());
        }

        // Update
        try {
            $this->comment->update($request->route('comment'), $request->all());
            $comment = $this->comment->find($request->route('comment'));
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success($comment);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request)
    {
        $user = $request->user();

        try {
            $comment = $this->comment->find($request->route('comment'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.comment.not_found'));
        }

        // Authorize
        if ($user->id != $comment->user->id) {
            return $this->apiResponse->unauthorized(trans('api_response.v1.comment.no_permission'));
        }

        // Delete
        try {
            $this->comment->delete($request->route('comment'));
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success(trans('api_response.v1.comment.delete_success'));
    }
}