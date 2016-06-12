<?php
namespace App\Repositories\Criteria;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class UserRepositorySearchCriteria implements CriteriaInterface
{

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @var \Eloquent $model
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($ids = $this->request->get('ids'))) {
            $model = $model->whereIn('id', $ids);
        }

        return $model;
    }
}