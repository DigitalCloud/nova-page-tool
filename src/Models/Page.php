<?php

namespace DigitalCloud\PageTool\Models;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use SoftDeletes, HasSlug, HasTranslations;

    public $translatable = ['title'];

    protected $fillable = [
        'title', 'slug', 'content', 'template', 'featured_image', 'parent_id', 'order', 'status', 'visibility', 'password', 'scheduled_for'
    ];

    protected $dates = [
        'scheduled_for',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
    }
	
	/**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && ! is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }
        return parent::setAttribute($key, $value);
    }
}
