<?php

namespace App\Filament\User\Resources\WinnerResource\Pages;

use App\Filament\User\Resources\WinnerResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListWinners extends ListRecords
{
    protected static string $resource = WinnerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
