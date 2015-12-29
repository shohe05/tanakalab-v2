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

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->model->where($column, $operator, $value, $boolean);
    }
}