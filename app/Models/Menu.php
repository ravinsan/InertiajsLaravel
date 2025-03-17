<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'url', 'parent_id', 'order_id', 'icon_code', 'status', 'created_by'];

    public static function getAllParentActiveMenu(){
        return Menu::where('status',1)->where('parent_id', 0)->get();
    }

    public function parent()
    {
    	return $this->belongsTo(Menu::class, 'parent_id')->select('id', 'name');
    }
}
