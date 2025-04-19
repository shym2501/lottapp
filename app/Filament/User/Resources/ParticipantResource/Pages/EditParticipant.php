<?php

namespace App\Filament\User\Resources\ParticipantResource\Pages;

use App\Filament\User\Resources\ParticipantResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditParticipant extends EditRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): string
    {
        // Ambil data peserta terakhir (yang baru dibuat)
        $participant = $this->record;

        // Arahkan ke halaman index peserta berdasarkan form_id
        return route('filament.user.resources.participants.index', [
            'tableFilters[form_id][value]' => $participant->form_id,
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ubah struktur nested ke flat agar cocok dengan key form
        if (isset($data['data']) && is_array($data['data'])) {
            $flat = [];

            foreach ($data['data'] as $key => $item) {
                $flat[$key] = $item['value'] ?? null;
            }

            $data['data'] = $flat;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

        return $data;
    }
}
