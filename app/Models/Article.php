<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Collection;
use cebe\markdown\GithubMarkdown as MarkdownParser;

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
    public function getParsedBodyAttribute()
    {
        $parser = new MarkdownParser;
        $parser->html5 = true;
        $parser->keepListStartNumber = true;
        $parser->enableNewlines = true;
        return $parser->parse($this->body);
    }
}
