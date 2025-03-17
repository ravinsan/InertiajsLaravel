<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'permission_id', 'access'];

    public static function addRolePermissions($role_id,$permission_id,$data){
        return RolePermission::updateOrCreate(['role_id'=>$role_id,'permission_id'=>$permission_id],$data);
    }

    public static function getAllCheckedPermissions($id){
        return RolePermission::where('role_id',$id)->pluck('permission_id');
    }
}
