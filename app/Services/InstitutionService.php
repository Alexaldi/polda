<?php

namespace App\Services;

use App\Interfaces\InstitutionRepositoryInterface;

class InstitutionService
{
    public function __construct(private InstitutionRepositoryInterface $repo)
    {
    }

    public function getAllForDatatable()
    {
        return $this->repo->getAllForDatatable();
    }

    public function getAllOrderedByName()
    {
        return $this->repo->getAllOrderedByName();
    }

    public function getTypes()
    {
        return $this->repo->getDistinctTypes();
    }

    public function getById($id)
    {
        return $this->repo->findById($id);
    }

    public function store(array $data): array
    {
        $institution = $this->repo->store($data);

        return [
            'status' => (bool) $institution,
            'message' => $institution ? 'Institusi berhasil ditambahkan' : 'Gagal menambahkan institusi',
            'data' => $institution,
        ];
    }

    public function update($id, array $data): array
    {
        $institution = $this->repo->update($id, $data);

        return [
            'status' => (bool) $institution,
            'message' => $institution ? 'Institusi berhasil diperbarui' : 'Institusi tidak ditemukan atau gagal diperbarui',
            'data' => $institution,
        ];
    }

    public function delete($id): array
    {
        $deleted = $this->repo->delete($id);

        return [
            'status' => (bool) $deleted,
            'message' => $deleted ? 'Institusi berhasil dihapus' : 'Institusi tidak ditemukan atau gagal dihapus',
            'data' => null,
        ];
    }
}
