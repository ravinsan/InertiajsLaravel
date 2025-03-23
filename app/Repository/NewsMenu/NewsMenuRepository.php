<?php

namespace App\Repository\NewsMenu;
use App\Models\NewsMenu;
use function is;
use function is_null;
use Auth;

class NewsMenuRepository implements NewsMenuInterface{

    public function getAll($data)
    {
        $records = NewsMenu::with('parent');
        $sortColumn = !empty($data['sort_column']) ? $data['sort_column'] : 'id';
        $sortOrder = !empty($data['sort_order']) ? $data['sort_order'] : 'desc';

        $allowedColumns = ['id', 'name', 'parent_id', 'slug', 'order_id', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }

        // Text search
        if (array_key_exists('search', $data)) {
            $records->where(function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['search'] . '%');
                $query->orWhere('slug', 'like', '%' . $data['search'] . '%');
                $query->orWhereHas('parent', function ($q) use ($data) {
                    $q->where('name', 'like', '%' . $data['search'] . '%');
                });
            });
        }
        // Status
        if (isset($data['status']) && $data['status'] !== 'all') {
            $records->where('status', $data['status']);
        }
        //Mega Menu Status
        if (isset($data['mega_menu_status']) && $data['mega_menu_status'] !== 'all') {
            $records->where('mega_menu_status', $data['mega_menu_status']);
        }
        $records = $records->orderBy($sortColumn, $sortOrder);
        $records = $records->paginate($data['per_page']);

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