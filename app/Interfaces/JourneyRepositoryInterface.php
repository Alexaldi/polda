<?php

namespace App\Interfaces;

interface JourneyRepositoryInterface
{
    public function store(array $data);
    public function getByReportId($reportId);
    public function findById($id);
}
