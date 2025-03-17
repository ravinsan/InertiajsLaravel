<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'page_type',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function NewsMenu()
    {
        return $this->belongsTo(NewsMenu::class, 'page_id', 'id');
    }

    public function Subnewssubmenu()
    {
        return $this->belongsTo(NewsMenu::class, 'page_id', 'id');
    }

    public function NewsDetail()
    {
        return $this->belongsTo(NewsDetail::class, 'page_id', 'id');
    }
}

