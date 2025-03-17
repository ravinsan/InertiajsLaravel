<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'role_level',
        'status',
        'created_by',
    ];

    public static function addRoles($id,$data){ //use
        return Role::updateOrCreate(['id'=>$id],$data);
    }
}
