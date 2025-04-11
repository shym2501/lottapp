<?php

namespace App\Filament\User\Widgets;

use App\Models\Form;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class FormOverview extends Widget
{
    protected static string $view = 'filament.user.widgets.form-overview';

    protected int|string|array $columnSpan = 'full';

    public function form()
    {
        return Form::where('user_id', Auth::id())->first();
    }
}
