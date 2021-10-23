<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Downloaders\MangaImageDownloader;

class Image extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'outer_link', 'inner_link'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return static
     */
    public static function updateOrCreate(array $attributes, array $values = array())
    {
        $instance = static::firstOrNew($attributes);

        $instance->fill($values)->save();

        return $instance;
    }

    public function getOuterLinkAttribute($url)
    {
        $link = url("api/image/?url=$url");
        return $link;
    }
}