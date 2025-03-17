<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_detail_id',
        'image',
        'image_url',
        'status',
    ];

    function news_detail()
    {
        return $this->belongsTo(NewsDetail::class, 'news_detail_id')->select('id', 'title');
    }
}
