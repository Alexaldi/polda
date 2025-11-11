<?php

namespace App\Services;

use App\Repositories\JourneyRepository;

class JourneyService
{
    protected $journeyRepository;

    public function __construct(JourneyRepository $journeyRepository)
    {
        $this->journeyRepository = $journeyRepository;
    }

    public function storeJourney(array $data)
    {
        return $this->journeyRepository->createJourney($data);
    }
}
