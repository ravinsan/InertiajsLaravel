<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VideoNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'region_id',
        'country_region_id',
        'state_region_id',
        'title',
        'thumbnail_image',
        'url_status',
        'video_url',
        'status',
        'is_live',
        'created_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
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

}
