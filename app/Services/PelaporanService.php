<?php

namespace App\Services;

use App\Repositories\PelaporanRepository;
use Illuminate\Support\Facades\DB;

class PelaporanService
{
    protected $repo;

    public function __construct(PelaporanRepository $repo)
    {
        $this->repo = $repo;
    }

    public function datatables($filter_q = null)
    {
        return $this->repo->getDataTableQuery($filter_q);
    }

    public function store(array $data)
    {
        return $this->repo->store($data);
    }

    public function getById($id)
    {
        return $this->repo->getById($id);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
