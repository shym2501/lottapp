<?php

namespace App\Filament\User\Resources\FormBuilderResource\Pages;

use App\Filament\User\Resources\FormBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFormBuilder extends ViewRecord
{
    protected static string $resource = FormBuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
