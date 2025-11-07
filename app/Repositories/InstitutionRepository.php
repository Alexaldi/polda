<?php

namespace App\Repositories;

use App\Models\Institution;
use App\Interfaces\InstitutionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class InstitutionRepository implements InstitutionRepositoryInterface
{
    public function query(): Institution
    {
        return new Institution();
    }

    public function getAllOrderedByName()
    {
        return $this->query()->orderBy('name')->get();
    }

    public function getAllForDatatable(): Builder
    {
        return $this->query()->newQuery()->select('institutions.*')->orderBy('institutions.created_at', 'desc');
    }

    public function store($payload)
    {
        return $this->query()->create($payload);
    }

    public function findById($id)
    {
        return $this->query()->find($id);
    }

    public function update($id, $payload)
    {
        $institution = $this->findById($id);

        if (!$institution) {
            return null;
        }

        $institution->update($payload);

        return $institution->refresh();
    }

    public function delete($id)
    {
        $institution = $this->findById($id);

        if (!$institution) {
            return false;
        }

        return (bool) $institution->delete();
    }

    public function getDistinctTypes()
    {
        return $this->query()
            ->newQuery()
            ->select('type')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
    }
}
