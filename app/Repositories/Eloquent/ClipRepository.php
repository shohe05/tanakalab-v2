<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryAbstract;
use App\Repositories\Contracts\ClipRepositoryInterface;
use App\Models\Clip;

class ClipRepository extends EloquentRepositoryAbstract implements ClipRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Clip::class;
    }

}
