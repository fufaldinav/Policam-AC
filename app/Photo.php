<?php

namespace App;

use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Photo
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $hash
 * @property int|null $person_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Person|null $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereUpdatedAt($value)
 */
class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash', 'person_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'person',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'person_id' => 'integer',
    ];


    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public static function saveFileThenCreate($file)
    {
        $file_hash = hash_file('md5', $file);

        $photo = Image::make(file_get_contents($file))->encode('jpg')->save("photos/$file_hash.jpg");

        Image::make($photo)->resize(null, 320, function ($constraint) {
            $constraint->aspectRatio();
        })->save("photos/thumbnails/$file_hash.jpg");

        return self::firstOrCreate(['hash' => $file_hash]);
    }

    public function deleteFile(): self
    {
        Storage::disk('photos')->delete("$this->hash.jpg");
        Storage::disk('photos')->delete("/thumbnails/$this->hash.jpg");

        return $this;
    }
}
