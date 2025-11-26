<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
    public function createParticipant(array $data);
    public function getUserIdsByDivisionIds(array $divisionIds): Collection;
}

