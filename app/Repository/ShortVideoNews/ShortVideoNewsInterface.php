<?php

namespace App\Repository\ShortVideoNews;

use App\Models\ShortVideoNews;

interface ShortVideoNewsInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function isLiveStatus($id);
}