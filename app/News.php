<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\News
 *
 * @property int $id
 * @property string $header
 * @property string $content
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\News whereUserId($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'header', 'content', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function formattedDate(string $format)
    {
        $timestamp = strtotime($this->created_at);

        return date($format, $timestamp);
    }
}
