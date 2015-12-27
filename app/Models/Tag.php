<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tag extends Model {

	protected $fillable = ['name'];

    /**
     * タグ名の配列から、タグを作成or同名タグがあれば取得
     *
     * @param array $tag_names
     * @return Collection
     */
    public static function FirstOrCreateByNames(array $tag_names)
    {
        $tag_names = array_filter($tag_names);
        $tags = new Collection();
        foreach ($tag_names as $tag_name) {
            $tags->push(static::firstOrCreate(['name' => $tag_name]));
        }
        return $tags;
    }

}
