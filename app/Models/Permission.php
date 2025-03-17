<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['menu_id', 'sub_menu_id', 'name', 'url', 'guard_name', 'status', 'created_by'];

    public function Menu()
    {
    	return $this->belongsTo(Menu::class, 'menu_id')->select('id', 'name');
    }

    public function SubMenu()
    {
    	return $this->belongsTo(Menu::class, 'sub_menu_id')->select('id', 'name');
    }
}
