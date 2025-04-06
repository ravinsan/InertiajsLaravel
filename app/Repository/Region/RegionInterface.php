<?php

namespace App\Repository\Region;

use App\Models\Region;

interface RegionInterface{
    public function getAll($data);
    public function store($data);
    public function find($id);
    public function update($data, $id);
    public function delete($id);
    public function statusChange($id);
    public function getRegion();
    public function megaMenustatusChange($id);
    public function getSubRegion($id);
    public function frontendMenustatusChange($id);
    public function pageDesignStatusChange($id);
}