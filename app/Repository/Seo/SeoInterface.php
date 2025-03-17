<?php

namespace App\Repository\Seo;

use App\Models\Page;

interface SeoInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function statusChange($id);
    public function delete($id);
}