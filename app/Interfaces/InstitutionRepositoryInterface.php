<?php

namespace App\Interfaces;

interface InstitutionRepositoryInterface
{
    /**
     * Return all institutions ordered by name.
     */
    public function getAllOrderedByName();

    /**
     * Base query for datatables listing.
     */
    public function getAllForDatatable();

    /**
     * Persist a new institution.
     */
    public function store($payload);

    /**
     * Find institution by id.
     */
    public function findById($id);

    /**
     * Update institution data.
     */
    public function update($id, $payload);

    /**
     * Delete institution by id.
     */
    public function delete($id);

    /**
     * Get distinct institution types for filter/form usage.
     */
    public function getDistinctTypes();
}