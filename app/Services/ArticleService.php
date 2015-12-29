<?php

namespace App\Services;

use App\Repositories\Contracts\ArticleRepositoryInterface as ArticleRepository;
use App\Repositories\Contracts\TagRepositoryInterface as TagRepository;
use App\Services\Contracts\ArticleServiceInterface;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Repositories\Contracts\ClipRepositoryInterface as ClipRepository;

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
     * @var Request
     */
    protected $request;

    /**
     * ArticleService constructor.
     * @param TagRepository $tagRepository
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
            'comments'=> $article->comments,
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
        $builder = null;
        foreach ($queries as $query) {
            $builder = \App\Models\Article::where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%' . $query . '%')->where('body', 'LIKE', '%' . $query . '%', 'or');
            });
        }
        $perPage = !is_null($perPage) ? $perPage : config('pagination.perPage');
        return $builder->paginate($perPage, ['*'], 'page', $page)->appends($request->all());
    }
}