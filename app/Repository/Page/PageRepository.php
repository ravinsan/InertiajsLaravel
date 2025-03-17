<?php

namespace App\Repository\Page;
use App\Models\Page;
use function is;
use function is_null;

class PageRepository implements PageInterface{

    public function getAll()
    {
        return Page::with('category')->orderBy('id', 'desc')->get();
    }

    public function store($data)
    {
        $store = Page::create($data);
        
        return $store;
    }

    public function find($id)
    {
        $edit = Page::find($id);
        
        return $edit;
    }

    public function update($data, $id)
    {
        $update = Page::find($id);
        $update = $update->update($data);
        return $update;
    }

    public function statusChange($id)
    {
        $page    = Page::where('id', $id)->first();
        if($page->status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        $value     = array('status' => $status);
        $change    = Page::where('id', $id)->update($value);
        return $change;
    }

    public function delete($id)
    {
        $delete = Page::find($id);
        $delete = $delete->delete();
        return $delete;
    }
}