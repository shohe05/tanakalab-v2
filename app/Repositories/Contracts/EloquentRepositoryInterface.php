<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

interface EloquentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and');
}