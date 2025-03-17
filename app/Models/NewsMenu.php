<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class NewsMenu extends Model
{
    use HasFactory, SoftDeletes, Sluggable;
    
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'status',
        'mega_menu_status',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NewsMenu::class, 'parent_id');
    }

    // Children relationship
    public function children(): HasMany
    {
        return $this->hasMany(NewsMenu::class, 'parent_id')->select('id', 'parent_id', 'name', 'slug');
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
