<?php

namespace App\Services;

use App\Repositories\ReportDataRepository;
use Illuminate\Database\Eloquent\Builder;

class ReportDataService
{
    public function __construct(protected ReportDataRepository $repository)
    {
    }

    public function buildQuery(array $filters = [], bool $withSorting = true): Builder
    {
        $query = $this->repository->applyFilters(
            $this->repository->baseQuery(),
            $filters
        );

        if ($withSorting) {
            $query = $this->repository->applySorting($query, $filters);
        }

        return $query;
    }

    public function totalCount(): int
    {
        return $this->repository->baseQuery()->count();
    }

    public function filteredCount(array $filters = []): int
    {
        return $this->buildQuery($filters, false)->count();
    }
}
