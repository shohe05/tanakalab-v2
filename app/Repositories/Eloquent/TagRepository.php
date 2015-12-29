<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryAbstract;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;

class TagRepository extends EloquentRepositoryAbstract implements TagRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tag::class;
    }

    /**
     * タグ名の配列から、タグを作成or同名タグがあれば取得
     *
     * @param array $tag_names
     * @return Collection
     */
    public function firstOrCreateByNames(array $tag_names)
    {
        $tag_names = array_filter($tag_names);
        $tags = new Collection();
        foreach ($tag_names as $tag_name) {
            $tags->push(Tag::firstOrCreate(['name' => $tag_name]));
        }
        return $tags;
    }

}
