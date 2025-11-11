<?php

namespace App\Repositories;

use App\Interfaces\JourneyRepositoryInterface;
use App\Models\ReportJourney;


class JourneyRepository implements JourneyRepositoryInterface
{
     public function store(array $data)
    {
        return ReportJourney::create($data);
    }

    public function getByReportId($reportId)
    {
        return ReportJourney::with('evidences')
            ->where('report_id', $reportId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function findById($id)
    {
        return ReportJourney::with('evidences')->findOrFail($id);
    }
}