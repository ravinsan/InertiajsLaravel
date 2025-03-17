<?php

namespace App\Repository\Menu;

interface MenuInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
}