<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'outer_link', 'inner_link', 'status', 'last_updated', 'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function authors()
    {   
        return $this->belongsToMany(Author::class, 'mangas_authors');
    }

    public function categories()
    {   
        return $this->belongsToMany(Category::class, 'mangas_categories');
    }

    public function chapters()
    {   
        return $this->hasMany(Chapter::class);
    }

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

}
