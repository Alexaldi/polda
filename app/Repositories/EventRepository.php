<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Support\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function createParticipant(array $data)
    {
        return EventParticipant::create($data);
    }

    public function getUserIdsByDivisionIds(array $divisionIds): Collection
    {
        return User::whereIn('division_id', $divisionIds)->pluck('id')->values();
    }
}

