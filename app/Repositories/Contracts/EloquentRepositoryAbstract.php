<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class EloquentRepositoryAbstract
 * @package App\Repositories\Contracts
 * @property \Illuminate\Database\Eloquent\Builder $model
 */
abstract class EloquentRepositoryAbstract extends BaseRepository implements EloquentRepositoryInterface
{
}