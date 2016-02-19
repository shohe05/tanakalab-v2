<?php

namespace App\Services;

use App\Repositories\Contracts\CommentRepositoryInterface as CommentRepository;
use App\Services\Contracts\CommentServiceInterface;
use App\Services\Contracts\NotifySlackServiceInterface;
use Validator;
use App\Services\Contracts\NotifySlackServiceInterface as NotifySlack;

class CommentService implements CommentServiceInterface
{

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    protected $notifySlack;

    /**
     * CommentService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository, NotifySlack $notifySlack)
    {
        $this->commentRepository = $commentRepository;
        $this->notifySlack = $notifySlack;
    }

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validator($input)
    {
        return Validator::make($input, [
            'body' => 'required',
        ]);
    }

    /**
     * @param $user_id
     * @param $article_id
     * @param $input
     * @return mixed
     */
    public function create($user_id, $article_id, $input)
    {
        $input = array_merge($input, ['article_id' => $article_id, 'user_id' => $user_id]);
        $comment = $this->commentRepository->create($input);
        $this->notifySlack->notifyCommentCreated($comment);
        return $comment;
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input)
    {
        return $this->commentRepository->update($input, $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->commentRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->commentRepository->find($id);
    }

}