<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryCriteriaInterface;

interface ArticleRepositoryInterface extends EloquentRepositoryInterface, RepositoryCriteriaInterface
{
}