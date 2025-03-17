<?php

namespace App\Repository\Permission;

interface PermissionInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
}