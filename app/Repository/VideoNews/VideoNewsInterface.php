<?php

namespace App\Repository\VideoNews;

use App\Models\VideoNews;

interface VideoNewsInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function isLiveStatus($id);
}