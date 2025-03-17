<?php

namespace App\Repository\Category;

use App\Models\Category;

interface CategoryInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function getCategory();
    public function megaMenustatusChange($id);
    public function getSubCategory($id);
    public function frontendMenustatusChange($id);
    public function pageDesignStatusChange($id);
}