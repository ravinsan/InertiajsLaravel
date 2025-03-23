<?php

namespace App\Repository\Category;
use App\Models\Category;
use function is;
use function is_null;
use Auth;

class CategoryRepository implements CategoryInterface{

    public function getAll($data)
    {
        $query = Category::with(['parent', 'children']);

        $sortColumn = !empty($data['sort_column']) ? $data['sort_column'] : 'id';
        $sortOrder = !empty($data['sort_order']) ? $data['sort_order'] : 'desc';

        $allowedColumns = ['id', 'name', 'parent_id', 'slug', 'order_id', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }

        // Text search
        if (array_key_exists('search', $data)) {
            $query->where(function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['search'] . '%');
                $query->orWhere('slug', 'like', '%' . $data['search'] . '%');
                $query->orWhereHas('parent', function ($q) use ($data) {
                    $q->where('name', 'like', '%' . $data['search'] . '%');
                });
            });
        }
        // Status
        if (isset($data['status']) && $data['status'] !== 'all') {
            $query->where('status', $data['status']);
        }
        //Mega Menu Status
        if (isset($data['mega_menu_status']) && $data['mega_menu_status'] !== 'all') {
            $query->where('mega_menu_status', $data['mega_menu_status']);
        }
        //Frontend Menu Status
        if (isset($data['frontend_menu_status']) && $data['frontend_menu_status'] !== 'all') {
            $query->where('frontend_menu_status', $data['frontend_menu_status']);
        }
        // Page Design Status
        if (isset($data['page_design_status']) && $data['page_design_status'] !== 'all') {
            $query->where('page_design_status', $data['page_design_status']);
        }
        
        $query->orderBy($sortColumn, $sortOrder);

        return $query->paginate($data['pagen']);
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