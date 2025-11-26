<?php

namespace App\Services;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;

class EventService
{
    public function __construct(private EventRepositoryInterface $repo)
    {
    }

    public function attachParticipantsAndResolveUsers(Event $event, array $participants): array
    {
        $divisions = [];
        foreach ($participants as $p) {
            $divisions[] = $p['division_id'];
            $this->repo->createParticipant([
                'event_id' => $event->id,
                'division_id' => $p['division_id'],
                'is_required' => (bool) ($p['is_required'] ?? true),
                'note' => $p['note'] ?? null,
            ]);
        }

        $userIds = $this->repo->getUserIdsByDivisionIds($divisions);

        return [
            'user_ids' => $userIds,
        ];
    }
}

