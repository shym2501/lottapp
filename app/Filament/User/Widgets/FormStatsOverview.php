<?php

namespace App\Filament\User\Widgets;

use App\Models\Form;
use App\Models\Participant;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class FormStatsOverview extends Widget
{
    protected static string $view = 'filament.user.widgets.form-stats-overview';

    protected int|string|array $columnSpan = 'full';

    public function getForm()
    {
        return Form::where('user_id', Auth::id())->first();
    }

    public function getParticipantCount(): int
    {
        $form = $this->getForm();
        return $form ? $form->participants()->count() : 0;
    }

    public function getDaysLeft(): ?int
    {
        $form = $this->getForm();
        if ($form && $form->end_date && now()->lt($form->end_date)) {
            return Carbon::parse($form->end_date)->diffInDays(now());
        }

        return null;
    }
}
