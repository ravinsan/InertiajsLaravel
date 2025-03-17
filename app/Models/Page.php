<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'image',
        'description',
        'short_description',
        'long_description',
        'status',
        'created_by',
    ];

    public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
