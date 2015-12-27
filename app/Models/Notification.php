<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Mail;

class Notification extends Model {

    protected $fillable = ['type', 'target_id', 'user_id'];

    const TYPE_ARTICLE_POSTED = 0;
    const TYPE_ARTICLE_UPDATED = 1;
    const TYPE_COMMENTED = 2;
    const TYPE_STOCKED = 3;

    const UNCHECKED = 0;
    const CHECKED = 1;

	public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeArticlePosted()
    {
        return static::TYPE_ARTICLE_POSTED;
    }

    public function typeArticleUpdated()
    {
        return static::TYPE_ARTICLE_UPDATED;
    }

    public function typeCommented()
    {
        return static::TYPE_COMMENTED;
    }

    public function typeStocked()
    {
        return static::TYPE_STOCKED;
    }

    /**
     * 通知の文言をHTMLで返す
     *
     * @return string\
     */
    public function render()
    {
        $html = '';
        switch ($this->type) {

            case $this->typeArticlePosted():
                $article = Article::find($this->target_id);
                $html = $article->user->name . 'さんが記事<a href="/notifications/' . $this->id . '/check">「' . $article->title . '」</a>を投稿しました。';
                break;

            case $this->typeArticleUpdated():
                $article = Article::find($this->target_id);
                $html = $article->user->name . 'さんが記事<a href="/notifications/' . $this->id . '/check">「' . $article->title . '」</a>を編集しました';
                break;

//            case $this->typeCommented():
//                $comment = Comment::find($this->target_id);
//                $article = $comment->article;
//                $html = $comment->user->name . 'さんがあなたの記事<a href="/notifications/' . $this->id . '/check">「' . $article->title . '」</a>にコメントしました';
//                break;
//
//            case $this->typeStocked():
//                $stock = Stock::find($this->target_id);
//                $article = $stock->article;
//                $html = $stock->user->name . 'さんが記事<a href="/notifications/' . $this->id . '/check">「' . $article->title . '」</a>をストックしました';
//                break;

        }

        return $html;
    }

    /**
     * 未読の通知のみを抽出するスコープ
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnchecked($query)
    {
        return $query->where('checked', '=', static::UNCHECKED);
    }

    /**
     * 通知に既読フラグをつける
     */
    public function check()
    {
        $this->checked = static::CHECKED;
        $this->save();
    }

    /**
     * 通知作成。
     *
     * @param $type 通知のタイプ
     * @param $target_instance 通知のタイプに応じたモデルのインスタンス
     */
    public function make($type, $target_instance)
    {
        $target_users = User::where('id', '!=', $target_instance->user->id)->get();
        foreach($target_users as $user) {
            $notification = $this->create([
                'type' => $type,
                'target_id' => $target_instance->id,
                'user_id' => $user->id,
            ]);

            //メール送信
            //TODO: 非同期で処理したいけど、とりあえずここで
            // $this->mailNotification($notification, $target_instance);
            $this->postSlack($notification, $target_instance);
        }
    }

    /**
     * 通知メールを送信
     *
     * @param $notification Notificationオブジェクト
     * @param $target_instance notification->typeに応じたインスタンス。記事更新の通知ならArticleオブジェクト、コメント通知ならCommentオブジェクト
     */
    public function mailNotification($notification, $target_instance)
    {
        switch ($notification->type) {
            case $this->typeArticlePosted():
                $this->sendMail($notification->user->email, '新しい記事が投稿されました', 'emails.notifications.article_posted', [
                    'to_user_name' => $notification->user->name,
                    'article' => $target_instance,
                    'notification' => $notification,
                ]);
                break;

            case $this->typeArticleUpdated():
                $this->sendMail($notification->user->email, '記事が編集されました', 'emails.notifications.article_updated', [
                    'to_user_name' => $notification->user->name,
                    'article' => $target_instance,
                    'notification' => $notification,
                ]);
                break;

//            case $this->typeCommented():
//                $this->sendMail($notification->user->email, '記事にコメントされました', 'emails.notifications.commented', [
//                    'to_user_name' => $notification->user->name,
//                    'comment' => $target_instance,
//                    'notification' => $notification,
//                ]);
//                break;
        }
    }

    /**
     * メール送信
     * TODO://ゆくゆくは別のクラスに切り出すべき.
     *
     * @param $to 宛先メールアドレス
     * @param $subject メールのタイトル
     * @param $view メールのビューファイルのパスをドット区切りで指定
     * @param $data ビューファイルに渡す変数の配列
     */
    public function sendMail($to, $subject, $view, $data)
    {
        Mail::send($view, $data, function($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function postSlack($article)
    {
        $slackPostUrl = 'https://hooks.slack.com/services/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';
        $payload = [
            'channel' => '#general',
            'username' => 'tanakalab',
            'text' => $article->user->name . 'さんがTanakaLabに記事<' . config('app.url') . '/articles/' . $article->id . '/show|' . $article->title . '>を投稿しました！',
            'icon_emoji' => ':ghost:',
        ];
        $params = ['payload' => json_encode($payload)];
        $ch = curl_init($slackPostUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        $error = curl_error($ch);
 
        curl_close($ch);
 
        if ($error) {
            throw new \Exception($error);
        }
 
        Log::info($response);
        Log::info($error);
    }

    public function scopeAboutArticle($query)
    {
        return $query->where('type', '=', $this->typeArticlePosted())->orWhere('type', '=', $this->typeArticleUpdated());
    }
}
