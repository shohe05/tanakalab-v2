<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryAbstract;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository extends EloquentRepositoryAbstract implements CommentRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Comment::class;
    }

}
