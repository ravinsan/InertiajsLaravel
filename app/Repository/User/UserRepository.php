<?php

namespace App\Repository\User;
use App\Models\User;
use Auth;

class UserRepository implements UserInterface{

    public function getAll()
    {
        return User::where('id', '<>', 1)->orderBy('id', 'desc')->get();
    }

    public function store($data)
    {
        $store = User::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = User::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = User::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = User::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $User    = User::where('id', $id)->first();
        if($User->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = User::where('id', $id)->update($value);
        return $change;
    }

}