<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'status',
        'mega_menu_status',
        'frontend_menu_status',
        'page_design_status',
		'image',
        'order_id',
        'created_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'  // Changed from 'title' to 'name'
            ]
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategory(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Children relationship
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->select('id', 'parent_id', 'name', 'slug');
    }

    // News relationship
    public function news(): HasMany
    {
        return $this->hasMany(NewsDetail::class, 'subcategory_id')->select('id', 'title', 'subcategory_id', 'thumbnail_image', 'created_at');
    }

    public function singleNews(): HasOne
    {
        return $this->hasOne(NewsDetail::class, 'subcategory_id')
                    ->where('status', 1)
                    ->select('news_details.id', 'news_details.title', 'news_details.slug', 'news_details.subcategory_id', 'news_details.thumbnail_image')
                    ->latestOfMany();
    }

    public function newsDetails(): HasMany
    {
        return $this->hasMany(NewsDetail::class, 'category_id');
    }

    public function categoryNews(): HasMany
    {
        return $this->hasMany(NewsDetail::class, 'category_id')->where('status', 1);
    }
    
    public function subcategoryNews(): HasMany
    {
        return $this->hasMany(NewsDetail::class, 'subcategory_id')->where('status', 1);
    }

    public function getparentCountAttribute(): int
    {
        return $this->parent()->count();
    }

    public function getchildrenCountAttribute(): int
    {
        return $this->children()->count();
    }

    
}
