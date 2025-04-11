<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use App\Filament\User\Widgets\FormOverview;
use App\Filament\User\Widgets\FormStatsOverview;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.user.pages.dashboard';

    protected static ?string $title = 'Home';

    protected function getHeaderWidgets(): array
    {
        return [
            // FormStatsOverview::class,
            FormOverview::class,
        ];
    }
}
