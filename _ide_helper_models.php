<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Article
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Clip[] $clips
 */
	class Article {}
}

namespace App\Models{
/**
 * App\Models\Clip
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Article $article
 */
	class Clip {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $user_id
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Article $article
 */
	class Comment {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification unchecked()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification aboutArticle()
 */
	class Notification {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
	class Tag {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $image_id
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Clip[] $clips
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 */
	class User {}
}

