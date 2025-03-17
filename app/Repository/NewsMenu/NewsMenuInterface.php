<?php

namespace App\Repository\NewsMenu;

use App\Models\NewsMenu;

interface NewsMenuInterface{
    public function getAll();
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function megaMenustatusChange($id);
    public function getNewsMenu();
    public function getSubNewsMenu($id);
}