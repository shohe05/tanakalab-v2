<?php
namespace App\Repositories\Criteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class ArticleRepositoryGetArticlesStockedBySpecifiedUserIdCriteria implements CriteriaInterface
{

    /**
     * @var int
     */
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @var \Eloquent $model
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->whereHas('clips', function($clip) {
            $clip->where('user_id', $this->user_id);
        });

        return $model;
    }
}