<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryAbstract;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleRepository extends EloquentRepositoryAbstract implements ArticleRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Article::class;
    }

}
