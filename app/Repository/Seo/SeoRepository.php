<?php

namespace App\Repository\Seo;
use App\Models\SEO;
use function is;
use function is_null;

class SeoRepository implements SeoInterface{

    public function getAll()
    {
        return SEO::with('page')->orderBy('id', 'desc')->get();
    }

    public function store($data)
    {
        $store = SEO::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = SEO::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = SEO::where('id', $id)->first();
        $update = $update->update($data);
        return $update;
    }

    public function statusChange($id)
    {
        $page    = SEO::where('id', $id)->first();
        if($page->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = SEO::where('id', $id)->update($value);
        return $change;
    }

    public function delete($id)
    {
        $delete = Seo::find($id);
        $delete = $delete->delete();
        return $delete;
    }
}