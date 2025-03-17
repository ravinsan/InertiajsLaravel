<?php

namespace App\Repository\NewsDetail;

use App\Models\NewsDetail;

interface NewsDetailInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function breakingNewsStatus($id);
}