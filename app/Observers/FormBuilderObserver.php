<?php

namespace App\Observers;

use App\Models\FormBuilder;
use App\Models\Participant;

class FormBuilderObserver
{
    public function saved(FormBuilder $formBuilder)
    {
        self::syncParticipants($formBuilder);
    }

    public function deleted(FormBuilder $formBuilder)
    {
        self::syncParticipants($formBuilder);
    }

    protected static function syncParticipants(FormBuilder $formBuilder)
    {
        $formId = $formBuilder->form_id;

        $fields = FormBuilder::where('form_id', $formId)
            ->where('is_active', true)
            ->get(['name', 'label']);

        $fieldKeys = $fields->pluck('name')->toArray();
        $fieldLabels = $fields->pluck('label', 'name')->toArray();

        $participants = Participant::where('form_id', $formId)->get();

        foreach ($participants as $participant) {
            $oldData = $participant->data ?? [];

            $newData = [];

            foreach ($fieldKeys as $key) {
                $newData[$key] = [
                    'label' => $fieldLabels[$key],
                    'value' => $oldData[$key]['value'] ?? null,
                ];
            }

            $participant->data = $newData;
            $participant->save();
        }
    }
}
