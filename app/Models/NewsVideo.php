<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NewsVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'news_detail_id',
        'video',
        'video_url',
        'status',
    ];

    function news_detail()
    {
        return $this->belongsTo(NewsDetail::class, 'news_detail_id')->select('id', 'title');
    }
}
