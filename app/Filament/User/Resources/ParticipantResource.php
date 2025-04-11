<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ParticipantResource\Pages;
use App\Filament\User\Resources\ParticipantResource\RelationManagers;
use App\Models\FormBuilder;
use App\Models\Participant;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationGroup = 'Manajemen Undian';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('form', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function () {
                $user = Auth::user();
                $formModel = $user->form;

                $formBuilderFields = FormBuilder::where('form_id', $formModel->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                $fields = [];

                // Tambahkan hidden form_id
                $fields[] = Hidden::make('form_id')->default($formModel->id);

                // Field dinamis dari FormBuilder
                foreach ($formBuilderFields as $field) {
                    $base = match ($field->type) {
                        'text' => Forms\Components\TextInput::make("data.{$field->name}"),
                        'textarea' => Forms\Components\Textarea::make("data.{$field->name}"),
                        'select' => Forms\Components\Select::make("data.{$field->name}")
                            ->options(collect(explode(',', $field->options))->mapWithKeys(fn($v) => [trim($v) => trim($v)])),
                        'checkbox' => Forms\Components\CheckboxList::make("data.{$field->name}")
                            ->options(collect(explode(',', $field->options))->mapWithKeys(fn($v) => [trim($v) => trim($v)])),
                        'file' => Forms\Components\FileUpload::make("data.{$field->name}")
                            ->directory('participants')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])  // validasi tipe
                            ->maxSize(2048)  // ukuran dalam kilobyte, jadi 2048 = 2MB
                            ->image()
                            ->helperText('Hanya file gambar (.jpg, .png, .webp), max 2MB')
                            ->label($field->label)
                            ->rules(['image', 'max:2048', 'mimes:jpg,jpeg,png,webp']),
                        default => Forms\Components\TextInput::make("data.{$field->name}"),
                    };

                    $base->label($field->label);

                    if ($field->is_required) {
                        $base->required();
                    }

                    $fields[] = $base;
                }

                return $fields;
            });
    }

    protected static function getDynamicColumns(): array
    {
        $columns = [];

        $user = auth()->user();
        $formModel = $user->form;

        if ($formModel) {
            $formBuilderFields = \App\Models\FormBuilder::where('form_id', $formModel->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            foreach ($formBuilderFields as $field) {
                if ($field->type === 'file') {
                    $columns[] = Tables\Columns\ImageColumn::make("data.{$field->name}")
                        ->label($field->label)
                        ->disk('public')  // Sesuaikan jika kamu pakai disk lain
                        ->defaultImageUrl(asset('images/placeholder.png'))  // Opsional: gambar default jika kosong
                        ->circular();  // atau .rounded() jika lebih suka kotak
                } else {
                    // Field biasa
                    $columns[] = Tables\Columns\TextColumn::make("data.{$field->name}")
                        ->label($field->label)
                        ->limit(50)
                        ->sortable()
                        ->searchable();
                }
            }
        }

        // Kolom created_at tetap ditambahkan
        $columns[] = Tables\Columns\TextColumn::make('created_at')
            ->label('Tanggal')
            ->date();

        return $columns;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getDynamicColumns())
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

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['form_id'] = $user->form->id ?? null;

        return $data;
    }
}
