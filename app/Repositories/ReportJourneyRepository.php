<?php

namespace App\Repositories;

use App\Interfaces\ReportJourneyRepositoryInterface;
use App\Models\ReportJourney;

class ReportJourneyRepository implements ReportJourneyRepositoryInterface
{
<<<<<<< HEAD
<<<<<<< HEAD
    public function store(array $data)
=======
    public function store(array $data): ReportJourney
>>>>>>> 02a3e64 (test: verify journey multi-upload success)
=======
    public function store(array $data): ReportJourney
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
    {
        return ReportJourney::create($data);
    }
}
