<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryCriteriaInterface;

interface TagRepositoryInterface extends EloquentRepositoryInterface, RepositoryCriteriaInterface
{
    public function firstOrCreateByNames(array $tag_names);
}