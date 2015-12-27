<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryCriteriaInterface;

interface UserRepositoryInterface extends EloquentRepositoryInterface, RepositoryCriteriaInterface
{
}