<?php

namespace App\Repository\VideoNews;
use App\Models\VideoNews;
use function is;
use function is_null;
use Auth;

class VideoNewsRepository implements VideoNewsInterface{

    public function getAll()
    {
        $records = VideoNews::with(['region', 'country', 'state'])->orderBy('id', 'desc');
        $records = $records->get();

        return $records;
    }

    public function store($data)
    {
        $store = VideoNews::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = VideoNews::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = VideoNews::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = VideoNews::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch       = VideoNews::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        
        $value      = array('status' => $status);
        $change     = VideoNews::where('id', $id)->update($value);
        return $change;
    }

    public function isLiveStatus($id)
    {
        $sch       = VideoNews::where('id', $id)->first();
        if($sch->is_live == '1'){
            $is_live = '0';
        }else{
            $is_live = '1';
        }
        
        $value      = array('is_live' => $is_live);
        $change     = VideoNews::where('id', $id)->update($value);
        return $change;
    }
}