<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\FormBuilderResource\Pages;
use App\Filament\User\Resources\FormBuilderResource\RelationManagers;
use App\Models\Form as FormModel;
use App\Models\FormBuilder;
use Filament\Forms\Components\{TextInput, Select, Toggle, Section, Hidden, Placeholder};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\{TextColumn, BooleanColumn, ToggleColumn};
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class FormBuilderResource extends Resource
{
    protected static ?string $model = FormBuilder::class;
    protected static ?string $navigationGroup = 'Manajemen Umum';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('form', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    public static function form(Form $form): Form
    {
        $formModel = FormModel::where('user_id', auth()->id())->first();
        return $form
            ->schema([
                Section::make($formModel?->title ?? 'Belum ada form')
                    ->description('Tambahkan Form yang akan diisi oleh peserta Exp: Nama, Email, No.Hp')
                    ->schema([
                        Hidden::make('form_id')->default($formModel?->id),
                        TextInput::make('label')
                            ->label('Label')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state, ?FormBuilder $record) {
                                $defaultNames = ['fullnama', 'email', 'contact-person'];

                                if (!$record || !in_array($record->name, $defaultNames)) {
                                    $set('name', Str::slug($state));
                                }
                            })
                            ->required(),
                        TextInput::make('name')
                            ->label('Slug')
                            ->readOnly()
                            ->required(),
                        Select::make('type')
                            ->label('Tipe Field')
                            ->options([
                                'text' => 'Text',
                                'email' => 'Email',  // Tambahkan opsi untuk email
                                'number' => 'Number',  // Tambahkan opsi untuk number
                                'textarea' => 'Textarea',
                                'select' => 'Select',
                                'checkbox' => 'Checkbox',
                                'file' => 'File Upload',
                            ])
                            ->required(),
                        TextInput::make('options')
                            ->label('Opsi (untuk select / checkbox)')
                            ->helperText('Pisahkan dengan koma'),
                        Toggle::make('is_required')->label('Wajib Diisi'),
                        Toggle::make('is_active')->label('Aktif?'),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('Label')->sortable(),
                TextColumn::make('name')->label('Slug')->sortable(),
                TextColumn::make('type')->label('Tipe'),
                ToggleColumn::make('is_required')->label('Wajib'),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('locked')
                    ->icon('heroicon-o-lock-closed')  // ikon gembok
                    ->tooltip('Field ini tidak bisa dihapus')
                    ->visible(fn($record) => in_array($record->name, ['fullname', 'email', 'contact-person']))
                    ->disabled(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => !in_array($record->name, ['fullname', 'email', 'contact-person'])),
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
            'index' => Pages\ListFormBuilders::route('/'),
            'create' => Pages\CreateFormBuilder::route('/create'),
            'view' => Pages\ViewFormBuilder::route('/{record}'),
            'edit' => Pages\EditFormBuilder::route('/{record}/edit'),
        ];
    }
}
