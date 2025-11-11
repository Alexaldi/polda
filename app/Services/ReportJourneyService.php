<?php

namespace App\Services;

use App\Interfaces\ReportJourneyRepositoryInterface;
use App\Models\ReportEvidence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportJourneyService
{
    public function __construct(
        protected ReportJourneyRepositoryInterface $repository
    ) {
    }

    public function store(array $data, array $files = [])
    {
        return DB::transaction(function () use ($data, $files) {
            $journey = $this->repository->store($data);

            foreach ($files as $file) {
                $storedPath = $file->store('evidences', 'public');

                ReportEvidence::create([
                    'report_journey_id' => $journey->id,
                    'report_id' => $journey->report_id,
                    'file_url' => Storage::url($storedPath),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }

            return $journey;
        });
    }
}
