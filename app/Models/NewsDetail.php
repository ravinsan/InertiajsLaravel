<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class NewsDetail extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'news_menu_id',
        'news_sub_menu_id',
        'category_id',
        'subcategory_id',
        'region_id',
        'country_region_id',
        'state_region_id',
        'title',
        'slug',
        'thumbnail_image',
        'videos_url',
        'short_description',
        'description',
        'status',
        'is_breaking_news',
        'created_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    function news_menu()
    {
        return $this->belongsTo(NewsMenu::class, 'news_menu_id')->select('id', 'name', 'slug');
    }

    function news_sub_menu()
    {
        return $this->belongsTo(NewsMenu::class, 'news_sub_menu_id')->select('id', 'name', 'slug');
    }

    function news_image()
    {
        return $this->hasMany(NewsImage::class, 'news_detail_id', 'id')->select('id', 'news_detail_id', 'image', 'image_url');
    }

    function news_video()
    {
        return $this->hasMany(NewsVideo::class, 'news_detail_id', 'id')->select('id', 'news_detail_id', 'video', 'video_url');
    }

    function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select('id', 'name','slug');
    }

    function subcategory()
    {
        return $this->belongsTo(Category::class,'subcategory_id')->select('id', 'name','slug');
    }

    function region()
    {
        return $this->belongsTo(Region::class, 'region_id')->select('id', 'name', 'slug');
    }

    function country()
    {
        return $this->belongsTo(Region::class, 'country_region_id', 'id')->select('id', 'name', 'slug');
    }

    function state()
    {
        return $this->belongsTo(Region::class, 'state_region_id', 'id')->select('id', 'name', 'slug');
    }

    function User()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name');
    }
}
