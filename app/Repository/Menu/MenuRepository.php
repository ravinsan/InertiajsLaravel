<?php

namespace App\Repository\Menu;
use App\Models\Menu;
use Auth;

class MenuRepository implements MenuInterface{

    public function getAll()
    {
        return Menu::query()->with('parent')->orderBy('id', 'desc')->get();
    }

    public function store($data)
    {
        $store = Menu::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Menu::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Menu::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = Menu::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $Menu    = Menu::where('id', $id)->first();
        if($Menu->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Menu::where('id', $id)->update($value);
        return $change;
    }

}