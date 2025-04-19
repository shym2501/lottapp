<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\WinnerResource\Pages;
use App\Filament\User\Resources\WinnerResource\RelationManagers;
use App\Models\Winner;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WinnerResource extends Resource
{
    protected static ?string $model = Winner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Undian';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Daftar Pemenang';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('form_id', auth()->user()->form->id)
            ->with(['participant']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('form_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('participant_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('participant_name')
                    ->label('Nama Pemenang')
                    ->getStateUsing(function ($record) {
                        $data = $record->participant->data ?? [];

                        foreach ($data as $key => $value) {
                            if (preg_match('/fullname/i', $key)) {
                                return $value;
                            }
                        }

                        return '-';
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('participant.kode_kupon')
                    ->label('Kode Kupon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Menang')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->headerActions([
                Action::make('resetWinners')
                    ->label('Reset Semua Pemenang')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        Winner::where('form_id', auth()->user()->form->id)->delete();

                        Notification::make()
                            ->title('Histori pemenang berhasil dihapus.')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListWinners::route('/'),
            'create' => Pages\CreateWinner::route('/create'),
            'edit' => Pages\EditWinner::route('/{record}/edit'),
        ];
    }
}
