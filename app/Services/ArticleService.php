<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface as ArticleRepository;
use App\Repositories\Contracts\TagRepositoryInterface as TagRepository;
use App\Repositories\Criteria\ArticleRepositoryGetArticlesStockedBySpecifiedUserIdCriteria;
use App\Services\Contracts\ArticleServiceInterface;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Repositories\Contracts\ClipRepositoryInterface as ClipRepository;
use App;

class ArticleService implements ArticleServiceInterface
{

    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var ClipRepository
     */
    protected $clipRepository;

    /**
     * ArticleService constructor.
     * @param ArticleRepository $articleRepository
     * @param TagRepository $tagRepository
     * @param ClipRepository $clipRepository
     * @param Request $request
     */
    public function __construct(ArticleRepository $articleRepository, TagRepository $tagRepository, ClipRepository $clipRepository, Request $request)
    {
        $this->articleRepository = $articleRepository;
        $this->tagRepository = $tagRepository;
        $this->clipRepository = $clipRepository;
        $this->request = $request;
    }

    /**
     * @param $request
     * @param null $perPage
     * @param int $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function get($request, $page = 1, $perPage = null)
    {
        if (!is_null($request->get('clip_by', null))) {
            $this->articleRepository->pushCriteria(new ArticleRepositoryGetArticlesStockedBySpecifiedUserIdCriteria($request->get('clip_by')));
        }

        $perPage = !is_null($perPage) ? $perPage : config('pagination.perPage');
        return $this->articleRepository->paginate($perPage, ['*'], 'page', $page)->appends($request->all());
    }

    /**
     * @param \App\Models\Article[] $articles
     * @return array
     */
    public function formatForIndex($articles)
    {
        $data = [];
        foreach ($articles as $article) {
            $data[]= [
                'id' => $article->id,
                'title' => $article->title,
                'user_id' => $article->user_id,
                'user_name' => $article->user->name,
                'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
                'tags' => $article->tags->map(function($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                }),
                'comments'=> $article->comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'user_name' => $comment->user->name,
                        'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ];
        }
        return $data;
    }

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validator($input)
    {
        return Validator::make($input, [
            'article.title' => 'required|max:125',
            'article.body' => 'required',
            'tags' => 'array',
        ]);
    }

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validatorForUpdate($input)
    {
        return Validator::make($input, [
            'article.title' => 'max:125',
            'tags' => 'array',
        ]);
    }

    /**
     * @param $article_input
     * @param $tag_input
     * @return mixed
     */
    public function create($user, $article_input, $tag_input)
    {
        return DB::transaction(function() use($user, $article_input, $tag_input) {
            $article = $user->articles()->create($article_input);
            if (!empty($tag_input)) {
                $tags = $this->tagRepository->firstOrCreateByNames($tag_input);
                $article->syncTags($tags);
            }
            return $article;
        });
    }

    public function update($id, $article_input, $tag_input)
    {
        return DB::transaction(function() use($id, $article_input, $tag_input) {
            $this->articleRepository->update($article_input, $id);
            $article = $this->articleRepository->find($id);
            $tag_ids = $article->tags->lists('id');
            if (!empty($tag_input)) {
                foreach($tag_ids as $tag_id) {
                    DB::delete('delete from article_tag where tag_id = ? and article_id = ?', [$tag_id, $article->id]);
                }
                $tags = $this->tagRepository->firstOrCreateByNames($tag_input);
                $article->syncTags($tags);
            }
        });
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->articleRepository->find($id);
    }

    /**
     * @param $article
     * @return array
     */
    public function formatForShow($article)
    {
        return [
            'id' => $article->id,
            'user_id' => $article->user->id,
            'user_name' => $article->user->name,
            'title' => $article->title,
            'body' => $article->body,
            'created_at' => $article->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
            'tags' => $article->tags->map(function($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),
            'clips' => $article->clips->map(function($clip) {
                return [
                    'user_id' => $clip->user->id,
                    'user_name' => $clip->user->name,
                ];
            }),
            'comments'=> $article->comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user->name,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }

    public function formatForCreate($article)
    {
        return [
            'id' => $article->id,
            'user_id' => $article->user->id,
            'user_name' => $article->user->name,
            'title' => $article->title,
            'body' => $article->body,
            'created_at' => $article->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
            'tags' => $article->tags->map(function($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),
        ];
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->articleRepository->delete($id);
    }

    /**
     * @param $id
     * @param $user_id
     * @return mixed
     */
    public function clip($id, $user_id)
    {
        return $this->clipRepository->create([
            'article_id' => $id,
            'user_id' => $user_id,
        ]);
    }

    /**
     * @param $id
     * @param $user_id
     */
    public function unclip($id, $user_id)
    {
        $clip = $this->clipRepository->findWhere(['user_id' => $user_id, 'article_id' => $id])[0];
        $this->clipRepository->delete($clip->id);
    }

    /**
     * @param $request
     * @param int $page
     * @param null $perPage
     * @return mixed
     */
    public function search($request, $page = 1, $perPage = null)
    {
        $queries = preg_split('/[\s|\x{3000}]+/u', $request->get('query'));
        $model = App::make(Article::class);
        if (!is_null($request->get('clip_by', null))) {
            $model = $model->whereHas('clips', function($clip) use ($request) {
                $clip->where('user_id', $request->get('clip_by'));
            });
        }
        foreach ($queries as $query) {
            $model = $model->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%' . $query . '%')->where('body', 'LIKE', '%' . $query . '%', 'or');
            });
        }
        $perPage = !is_null($perPage) ? $perPage : config('pagination.perPage');
        return $model->paginate($perPage, ['*'], 'page', $page)->appends($request->all());
    }
}