<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'menu_id', 'is_parent'];

    public static function addRoleMenu($role_id,$menu_id,$data){
        return RoleMenu::updateOrCreate(['role_id'=>$role_id,'menu_id'=>$menu_id],$data);
    }
}
