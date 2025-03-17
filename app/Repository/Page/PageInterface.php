<?php

namespace App\Repository\Page;

use App\Models\Page;

interface PageInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function statusChange($id);
    public function delete($id);
}