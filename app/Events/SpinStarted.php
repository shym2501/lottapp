<?php

namespace App\Events;

use App\Models\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SpinStarted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Participant $participant;
    public int $formId;
    public int $userId;

    public function __construct(Participant $participant, int $formId, int $userId)
    {
        $this->participant = $participant;
        $this->formId = $formId;
        $this->userId = $userId;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('spin-display.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'spin.started';
    }

    public function broadcastWith(): array
    {
        return [
            'participant' => [
                'id' => $this->participant->id,
                'name' => $this->participant->name,
                'kode_kupon' => $this->participant->kode_kupon,
                'data' => $this->participant->data,
            ],
            'form_id' => $this->formId,
        ];
    }
}
