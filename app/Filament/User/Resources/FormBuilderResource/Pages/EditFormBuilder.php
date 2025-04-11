<?php

namespace App\Filament\User\Resources\FormBuilderResource\Pages;

use App\Filament\User\Resources\FormBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormBuilder extends EditRecord
{
    protected static string $resource = FormBuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
