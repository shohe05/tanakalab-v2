<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Markdown;
use Auth;
use Log;
use DB;
use Illuminate\Support\Collection;

class Article extends Model {

    protected $fillable = ['title', 'body', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    /**
     * bodyをMarkdown形式に変換して返す
     *
     * @return string
     */
    public function getMarkdownBody()
    {
        return Markdown::parse($this->body);
    }

    /**
     * 記事とタグを一度に更新
     *
     * @param $article_input
     * @param $tag_inputs
     * @return mixed
     */
    public function updateWithTags($article_input, $tag_inputs)
    {
        return DB::transaction(function() use($article_input, $tag_inputs) {
            $this->update($article_input);
            $tag_ids = $this->tags->lists('id');
            foreach($tag_ids as $tag_id) {
                DB::delete('delete from article_tag where tag_id = ? and article_id = ?', [$tag_id, $this->id]);
            }

            $tags = Tag::FirstOrCreateByNames($tag_inputs);
            $this->syncTags($tags);
        });
    }

    /**
     * 記事とタグの中間テーブルのレコードを作成
     *
     * @param Collection $tags
     */
    public function syncTags($tags)
    {
        $tag_ids = array_pluck($tags->toArray(), 'id');
        $this->tags()->sync($tag_ids);
    }

    /**
     * 記事と、記事に関連するレコードを一度に削除
     *
     * @throws \Exception
     */
    public function deleteWithRelations()
    {
        // TODO: SQL文使わない
        // TODO: Eloquent使わない
        Comment::where('article_id', '=', $this->id)->delete();
        Stock::where('article_id', '=', $this->id)->delete();
        $tag_ids = $this->tags->lists('id');
        foreach($tag_ids as $tag_id) {
            DB::delete('delete from article_tag where tag_id = ? and article_id = ?', [$tag_id, $this->id]);
        }
        $this->delete();
    }
}
