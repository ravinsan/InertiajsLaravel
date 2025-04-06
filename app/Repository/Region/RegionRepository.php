<?php

namespace App\Repository\Region;
use App\Models\Region;
use function is;
use function is_null;
use Auth;

class RegionRepository implements RegionInterface{

    public function getAll($data)
    {

        $records = Region::with('parent');
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
        // Frontend Menu Status
        if (isset($data['frontend_menu_status']) && $data['frontend_menu_status'] !== 'all') {
            $records->where('frontend_menu_status', $data['frontend_menu_status']);
        }
        // Page Design Status
        if (isset($data['page_design_status']) && $data['page_design_status'] !== 'all') {
            $records->where('page_design_status', $data['page_design_status']);
        }

        $records = $records->orderBy($sortColumn, $sortOrder);
        $records = $records->paginate($data['per_page']);
        $records->getCollection()->transform(function ($record) {
            $record->news_count = getNewsCountwithRegion($record->id);
            return $record;
        });
    
        return $records;
    }

    public function store($data)
    {
        $store = Region::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Region::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Region::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = Region::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch    = Region::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Region::where('id', $id)->update($value);
        return $change;
    }

    public function megaMenustatusChange($id)
    {
        $sch    = Region::where('id', $id)->first();
        if($sch->mega_menu_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('mega_menu_status' => $status);
        $change    = Region::where('id', $id)->update($value);
        return $change;
    }

    public function frontendMenustatusChange($id)
    {
        $sch    = Region::where('id', $id)->first();
        if($sch->frontend_menu_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('frontend_menu_status' => $status);
        $change    = Region::where('id', $id)->update($value);
        return $change;
    }

    public function pageDesignStatusChange($id)
    {
        $sch    = Region::where('id', $id)->first();
        if($sch->page_design_status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('page_design_status' => $status);
        $change    = Region::where('id', $id)->update($value);
        return $change;
    }

    public function getRegion()
    {
        $Region = Region::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        return $Region;
    }

    public function getSubRegion($id)
    {
        $Region = Region::where('parent_id', $id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        return $Region;
    }

}