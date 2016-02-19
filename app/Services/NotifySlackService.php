<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Comment;
use App\Services\Contracts\NotifySlackServiceInterface;

class NotifySlackService implements NotifySlackServiceInterface
{

    protected $webhook_url;

    protected $channel;

    protected $username;

    protected $icon_url;

    protected $force_notification;

    public function __construct()
    {
        $this->setup();
    }

    public function notifyArticleCreated(Article $article)
    {
        $text = $article->user->name." posted article ". $this->article_link($article);
        $this->notify($text);
    }

    public function notifyArticleUpdated(Article $article)
    {
        $text = $article->user->name." updated article ". $this->article_link($article);
        $this->notify($text);
    }

    public function notifyCommentCreated(Comment $comment)
    {
        $text = $comment->user->name." commented to ".$this->article_link($comment->article)."\n".$comment->body;
        $this->notify($text);
    }

    private function notify($text)
    {
        $param = $this->buildParam($text);
        $this->post($this->webhook_url, $param);
    }

    private function article_link(Article $article)
    {
        return $this->link($article->title, config('app.url').'/article/'.$article->id);
    }

    private function link($text, $url)
    {
        return "<".$url."|".$text.">";
    }

    private function buildParam($text)
    {
        if ($this->force_notification) {
            $text = "<!channel>\n".$text;
        }

        $payload = [
            'channel' => $this->channel,
            'username' => $this->username,
            'icon_url' => $this->icon_url,
            'text' => $text,
        ];

        return ['payload' => json_encode($payload)];
    }

    private function post($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function setup()
    {
        $config = config('slack');
        $this->webhook_url = array_get($config, 'webhook_url');
        $this->channel = array_get($config, 'channel');
        $this->username = array_get($config, 'username');
        $this->icon_url = array_get($config, 'icon_url');
        $this->force_notification = array_get($config, 'force_notification');
    }
}