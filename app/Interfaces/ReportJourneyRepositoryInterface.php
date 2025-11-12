<?php

namespace App\Interfaces;

use App\Models\ReportJourney;

<<<<<<< HEAD
interface ReportJourneyRepositoryInterface
{
    public function store(array $data): ReportJourney;
    public function store(array $data);
=======
use App\Models\ReportJourney;

interface ReportJourneyRepositoryInterface
{
    public function store(array $data): ReportJourney;
>>>>>>> 02a3e64 (test: verify journey multi-upload success)
}
