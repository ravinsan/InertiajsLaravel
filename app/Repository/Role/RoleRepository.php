<?php

namespace App\Repository\Role;
use App\Models\Role;
use function is;
use function is_null;
use Auth;

class RoleRepository implements RoleInterface{

    public function getAll()
    {
        $records = Role::orderBy('id', 'desc');
        $records = $records->get();

        return $records;
    }

    public function store($data)
    {
        $store = Role::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Role::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Role::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = Role::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch    = Role::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Role::where('id', $id)->update($value);
        return $change;
    }

}