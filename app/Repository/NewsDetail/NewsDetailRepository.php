<?php

namespace App\Repository\NewsDetail;
use App\Models\NewsDetail;
use function is;
use function is_null;
use Auth;

class NewsDetailRepository implements NewsDetailInterface{

    public function getAll()
    {
        $records = NewsDetail::with(['category', 'subcategory', 'region', 'country', 'state'])->orderBy('id', 'desc');
        $records = $records->get();
        
        return $records;
    }

    public function store($data)
    {
        $store = NewsDetail::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = NewsDetail::with(['news_image', 'news_video', 'category', 'subcategory', 'region', 'country', 'state'])->find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = NewsDetail::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function delete($id)
    {
        $delete = NewsDetail::find($id);
        $delete = $delete->delete();
        return $delete;
    }

    public function statusChange($id)
    {
        $sch    = NewsDetail::where('id', $id)->first();
        if($sch->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = NewsDetail::where('id', $id)->update($value);
        return $change;
    }

    public function breakingNewsStatus($id)
    {
        $sch    = NewsDetail::where('id', $id)->first();
        if($sch->is_breaking_news == '1'){
            $is_breaking_news = '0';
        }else{
            $is_breaking_news = '1';
        }
        $value     = array('is_breaking_news' => $is_breaking_news);
        $change    = NewsDetail::where('id', $id)->update($value);
        return $change;
    }

}