<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\FormResource\Pages;
use App\Filament\User\Resources\FormResource\RelationManagers;
use App\Models\Form as FormModel;
use Filament\Forms\Components\{TextInput, Textarea, Toggle, DatePicker, Select, Hidden, Section, Grid};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{TextColumn, BooleanColumn};
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Manajemen Undian';

    protected static ?string $label = 'Form Undian';

    protected static ?string $pluralLabel = 'Form Undian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Form')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul')
                                ->required(),
                            Hidden::make('user_id')->default(Auth::id()),
                            TextInput::make('user_name')
                                ->label('User')
                                ->default(Auth::user()?->name)
                                ->disabled()
                                ->dehydrated(false),
                            Textarea::make('description')
                                ->label('Deskripsi')
                                ->columnSpan(2),  // biar textarea ambil 1 baris penuh
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai'),
                            DatePicker::make('end_date')
                                ->label('Tanggal Berakhir'),
                        ]),
                    ]),
                Section::make('Pengaturan')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('is_active')
                                ->label('Aktifkan Form'),
                            Toggle::make('auto_verify_enabled')
                                ->label('Verifikasi QR'),
                            Toggle::make('is_closed')
                                ->label('Tutup Form'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                BooleanColumn::make('is_active')->label('Aktif'),
                BooleanColumn::make('is_closed')->label('Ditutup'),
                TextColumn::make('start_date')->label('Mulai')->date(),
                TextColumn::make('end_date')->label('Berakhir')->date(),
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
