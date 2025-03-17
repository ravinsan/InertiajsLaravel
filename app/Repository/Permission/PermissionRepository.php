<?php

namespace App\Repository\Permission;
use App\Models\Permission;
use Auth;

class PermissionRepository implements PermissionInterface{

    public function getAll()
    {
        return Permission::query()->with('Menu', 'SubMenu')->orderBy('id', 'desc')->get();
    }

    public function store($data)
    {
        $store = Permission::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Permission::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Permission::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = Permission::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $Permission    = Permission::where('id', $id)->first();
        if($Permission->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Permission::where('id', $id)->update($value);
        return $change;
    }

}