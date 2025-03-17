<?php

namespace App\Repository\NewsMenu;
use App\Models\NewsMenu;
use function is;
use function is_null;
use Auth;

class NewsMenuRepository implements NewsMenuInterface{

    public function getAll()
    {
        $records = NewsMenu::with('parent')->orderBy('id', 'desc');
        $records = $records->get();

        return $records;
    }

    public function store($data)
    {
        $store = NewsMenu::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = NewsMenu::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = NewsMenu::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = NewsMenu::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch    = NewsMenu::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = NewsMenu::where('id', $id)->update($value);
        return $change;
    }

    public function megaMenustatusChange($id)
    {
        $sch    = NewsMenu::where('id', $id)->first();
        if($sch->mega_menu_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('mega_menu_status' => $status);
        $change    = NewsMenu::where('id', $id)->update($value);
        return $change;
    }

    public function getNewsMenu()
    {
        $NewsMenu = NewsMenu::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        return $NewsMenu;
    }

    public function getSubNewsMenu($id)
    {
        $NewsMenu = NewsMenu::where('parent_id', $id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        return $NewsMenu;
    }

}