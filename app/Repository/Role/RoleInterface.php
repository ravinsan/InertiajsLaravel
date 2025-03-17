<?php

namespace App\Repository\Role;

use App\Models\Role;

interface RoleInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
}