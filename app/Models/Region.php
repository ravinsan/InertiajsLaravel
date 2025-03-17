<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'status',
		'frontend_menu_status',
        'page_design_status',
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

    function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    function subregion()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    // Children relationship
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')->select('id', 'parent_id', 'name', 'slug');
    }

    // News relationship
    public function news(): HasMany
    {
        return $this->hasMany(NewsDetail::class, 'news_sub_menu_id')->select('id', 'title', 'news_sub_menu_id', 'thumbnail_image', 'created_at');
    }

    public function singleNews(): HasOne
    {
        return $this->hasOne(NewsDetail::class, 'news_sub_menu_id')
                ->where('status', 1)
                ->select('news_details.id', 'news_details.title', 'news_details.slug', 'news_details.news_sub_menu_id', 'news_details.thumbnail_image')
                ->latestOfMany();
    }

    public function newsDetails()
    {
        return $this->hasMany(NewsDetail::class, 'news_menu_id');
    }
}
