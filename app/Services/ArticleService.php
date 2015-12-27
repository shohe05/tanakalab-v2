<?php

namespace App\Services;

use App\Repositories\Contracts\TagRepositoryInterface as TagRepository;
use App\Services\Contracts\ArticleServiceInterface;
use DB;
use Illuminate\Http\Request;

class ArticleService implements ArticleServiceInterface
{
    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(TagRepository $tagRepository, Request $request)
    {
        $this->tagRepository = $tagRepository;
        $this->request = $request;
    }

    public function create($article_input, $tag_input)
    {
        return DB::transaction(function() use($article_input, $tag_input) {
            $article = $this->request->user()->articles()->create($article_input);
            $tags = $this->tagRepository->firstOrCreateByNames($tag_input);
            $article->syncTags($tags);
            return $article;
        });
    }
}