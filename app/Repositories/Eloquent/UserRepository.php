<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryAbstract;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends EloquentRepositoryAbstract implements UserRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

}
