<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\FormBuilderResource\Pages;
use App\Filament\User\Resources\FormBuilderResource\RelationManagers;
use App\Models\FormBuilder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Select, Toggle, Section};
use Filament\Tables\Columns\{TextColumn, BooleanColumn};

class FormBuilderResource extends Resource
{
    protected static ?string $model = FormBuilder::class;
    protected static ?string $navigationGroup = 'Manajemen Undian';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('form_id')
                    ->label('Form Terkait')
                    ->relationship('form', 'title')
                    ->required(),
                Section::make('Pengaturan Field')
                    ->schema([
                        TextInput::make('label')->label('Label')->required(),
                        TextInput::make('name')->label('Name Field')->required(),
                        Select::make('type')
                            ->label('Tipe Field')
                            ->options([
                                'text' => 'Text',
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
                TextColumn::make('form.title')->label('Form'),
                TextColumn::make('label')->label('Label'),
                TextColumn::make('type')->label('Tipe'),
                BooleanColumn::make('is_required')->label('Wajib'),
                BooleanColumn::make('is_active')->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListFormBuilders::route('/'),
            'create' => Pages\CreateFormBuilder::route('/create'),
            'view' => Pages\ViewFormBuilder::route('/{record}'),
            'edit' => Pages\EditFormBuilder::route('/{record}/edit'),
        ];
    }
}
