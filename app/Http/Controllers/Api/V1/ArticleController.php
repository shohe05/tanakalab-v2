<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use App\Repositories\Contracts\TagRepositoryInterface as TagRepository;
use App\Services\Contracts\ArticleServiceInterface as ArticleService;
use Illuminate\Http\Request;
use App\Http\ApiResponse\Contracts\ApiResponseInterface as ApiResponse;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleController extends V1Controller
{

    /**
     * @var ArticleService
     */
    protected $article;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * ArticleController constructor.
     * @param ArticleService $article
     * @param ApiResponse $apiResponse
     */
    public function __construct(ArticleService $article, ApiResponse $apiResponse)
    {
        $this->article = $article;
        $this->apiResponse = $apiResponse;
        $this->middleware('jwt.auth');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $paginator = $this->article->get($request, $request->get('page'), $request->get('perPage'));
        $articles = $this->article->formatForIndex($paginator->items());
        return $this->apiResponse->setMeta([
            'has_more_pages' => $paginator->hasMorePages(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->count(),
        ])->success($articles);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request)
    {
        $article = $this->article->find($request->route('article'));
        $article = $this->article->formatForShow($article);
        return $this->apiResponse->success($article);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        // Validation
        $validator = $this->article->validator($request->all());
        if ($validator->fails()) {
            return $this->apiResponse->validationError(array_flatten($validator->errors()->toArray()));
        }

        // Create
        try {
            $user = $request->user();
            $article = $this->article->create($user, $request->get('article'), $request->get('tags'));
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->created($this->article->formatForCreate($article));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        try {
            $article = $this->article->find($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.article.not_found'));
        }

        // Authorize
        if ($user->id != $article->user->id) {
            return $this->apiResponse->unauthorized(trans('api_response.v1.article.no_permission'));
        }

        // Validation
        $validator = $this->article->validatorForUpdate($request->all());
        if ($validator->fails()) {
            return $this->apiResponse->validationError($validator->errors());
        }

        // Update
        try {
            $this->article->update($request->route('article'), $request->get('article'), $request->get('tags'));
            $article = $this->article->find($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success($this->article->formatForShow($article));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request)
    {
        $user = $request->user();

        try {
            $article = $this->article->find($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.article.not_found'));
        }

        // Authorize
        if ($user->id != $article->user->id) {
            return $this->apiResponse->unauthorized(trans('api_response.v1.article.no_permission'));
        }

        // Delete
        try {
            $this->article->delete($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success(trans('api_response.v1.article.delete_success'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clip(Request $request)
    {
        $user = $request->user();

        try {
            $article = $this->article->find($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.article.not_found'));
        }

        try {
            $this->article->clip($article->id, $user->id);
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->created(trans('api_response.v1.article.clip_success'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unclip(Request $request)
    {
        $user = $request->user();

        try {
            $article = $this->article->find($request->route('article'));
        } catch (Exception $e) {
            return $this->apiResponse->notFound(trans('api_response.v1.article.not_found'));
        }

        try {
            $this->article->unclip($article->id, $user->id);
        } catch (Exception $e) {
            return $this->apiResponse->internalServerError();
        }

        return $this->apiResponse->success(trans('api_response.v1.article.unclip_success'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $this->article->search($request, $request->get('page'), $request->get('perPage'));
        return $this->apiResponse->setMeta([
            'current' => $paginator->currentPage(),
            'has_more_pages' => $paginator->hasMorePages(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->count(),
        ])->success($this->article->formatForIndex($paginator->items()));
    }

    public function tags(TagRepository $tagRepository)
    {
        return $this->apiResponse->success($tagRepository->all());
    }
}