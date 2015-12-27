<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1Controller;
use App\Repositories\Contracts\ArticleRepositoryInterface as ArticleRepository;
use Illuminate\Http\Request;

class ArticleController extends V1Controller
{

    /**
     * @var ArticleRepository
     */
    protected $repo;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return response()->json('index');
    }

    public function create(Request $request)
    {
        $article_input = $request->get('article');
        $tag_inputs = $request->get('tags');

        $article = $this->repo->createWithTags($article_input, $tag_inputs);

        return response()->json('create');
    }
}