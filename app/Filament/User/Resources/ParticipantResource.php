<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ParticipantResource\Pages;
use App\Filament\User\Resources\ParticipantResource\RelationManagers;
use App\Models\Form as FormModel;
use App\Models\Participant;
use Filament\Forms\Components\{TextInput, Toggle, Select};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{TextColumn, BooleanColumn};
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Undian';

    protected static ?string $label = 'Peserta';

    protected static ?string $pluralLabel = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('form_id')
                    ->label('Form')
                    ->relationship('form', 'title')
                    ->required(),
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('email')->email()->label('Email'),
                TextInput::make('phone')->label('No HP'),
                TextInput::make('code')->label('Kode Unik')->required()->unique(ignoreRecord: true),
                Toggle::make('verified')->label('Terverifikasi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('form.title')->label('Form'),
                TextColumn::make('code')->label('Kode'),
                BooleanColumn::make('verified')->label('QR Verified'),
                TextColumn::make('created_at')->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
