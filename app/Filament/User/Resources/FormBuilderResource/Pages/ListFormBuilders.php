<?php

namespace App\Filament\User\Resources\FormBuilderResource\Pages;

use App\Filament\User\Resources\FormBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormBuilders extends ListRecords
{
    protected static string $resource = FormBuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
