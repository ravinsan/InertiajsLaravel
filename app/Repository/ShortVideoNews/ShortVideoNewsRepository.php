<?php

namespace App\Repository\ShortVideoNews;
use App\Models\ShortVideoNews;
use function is;
use function is_null;
use Auth;

class ShortVideoNewsRepository implements ShortVideoNewsInterface{

    public function getAll()
    {
        $records = ShortVideoNews::with(['region', 'country', 'state'])->orderBy('id', 'desc');
        $records = $records->get();

        return $records;
    }

    public function store($data)
    {
        $store = ShortVideoNews::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = ShortVideoNews::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = ShortVideoNews::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = ShortVideoNews::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch       = ShortVideoNews::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        
        $value      = array('status' => $status);
        $change     = ShortVideoNews::where('id', $id)->update($value);
        return $change;
    }

    public function isLiveStatus($id)
    {
        $sch       = ShortVideoNews::where('id', $id)->first();
        if($sch->is_live == '1'){
            $is_live = '0';
        }else{
            $is_live = '1';
        }
        
        $value      = array('is_live' => $is_live);
        $change     = ShortVideoNews::where('id', $id)->update($value);
        return $change;
    }
}