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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil form builder yang aktif
        $formModel = auth()->user()->form;
        $formBuilderFields = \App\Models\FormBuilder::where('form_id', $formModel->id)
            ->where('is_active', true)
            ->get();

        $nestedData = [];

        foreach ($formBuilderFields as $field) {
            $fieldKey = $field->name;
            $label = $field->label;

            $value = $data['data'][$fieldKey] ?? null;

            $nestedData[$fieldKey] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        $data['data'] = $nestedData;
        $data['form_id'] = $formModel->id;

        return $data;
    }
}
