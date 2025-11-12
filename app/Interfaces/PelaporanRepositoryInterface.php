<?php

namespace App\Interfaces;

interface PelaporanRepositoryInterface
{
    public function getDataTableQuery(string $filter = null);
    public function store(array $data);
    public function getById($id);
    public function update($id, array $data);
    public function delete($id);
}
