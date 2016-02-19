<?php

namespace App\Services\Contracts;

use App\Models\Article;
use App\Models\Comment;

interface NotifySlackServiceInterface
{
    public function notifyArticleCreated(Article $article);

    public function notifyArticleUpdated(Article $article);

    public function notifyCommentCreated(Comment $comment);
}