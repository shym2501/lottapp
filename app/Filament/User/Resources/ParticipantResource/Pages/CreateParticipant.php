<?php

namespace App\Filament\User\Resources\ParticipantResource\Pages;

use App\Filament\User\Resources\ParticipantResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;

    public function getRedirectUrl(): string
    {
        // Ambil data peserta terakhir (yang baru dibuat)
        $participant = $this->record;

        // Arahkan ke halaman index peserta berdasarkan form_id
        return route('filament.user.resources.participants.index', [
            'tableFilters[form_id][value]' => $participant->form_id,
        ]);
    }
}
