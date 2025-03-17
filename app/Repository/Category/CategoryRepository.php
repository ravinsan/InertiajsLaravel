<?php

namespace App\Repository\Category;
use App\Models\Category;
use function is;
use function is_null;
use Auth;

class CategoryRepository implements CategoryInterface{

    public function getAll()
    {
        $records = Category::with(['parent', 'children'])->orderBy('id', 'desc');
        $records = $records->get();

        return $records;
    }

    public function store($data)
    {
        $store = Category::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Category::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Category::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = Category::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch    = Category::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Category::where('id', $id)->update($value);
        return $change;
    }

    public function megaMenustatusChange($id)
    {
        $sch    = Category::where('id', $id)->first();
        if($sch->mega_menu_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('mega_menu_status' => $status);
        $change    = Category::where('id', $id)->update($value);
        return $change;
    }

    public function frontendMenustatusChange($id)
    {
        $sch    = Category::where('id', $id)->first();
        if($sch->frontend_menu_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('frontend_menu_status' => $status);
        $change    = Category::where('id', $id)->update($value);
        return $change;
    }

    public function pageDesignStatusChange($id)
    {
        $sch    = Category::where('id', $id)->first();
        if($sch->page_design_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('page_design_status' => $status);
        $change    = Category::where('id', $id)->update($value);
        return $change;
    }

    public function getCategory()
    {
        $category = Category::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        return $category;
    }

    public function getSubCategory($id)
    {
        $category = Category::where('parent_id', $id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        return $category;
    }

}