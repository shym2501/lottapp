<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CouponResource\Pages;
use App\Filament\User\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use App\Models\Form as FormModel;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Slider;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationGroup = 'Manajemen Undian';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('prefix')
                    ->label('Prefix Kupon')
                    ->required()
                    ->maxLength(10)
                    ->disabled(fn($get) => !$get('is_active')),
                Toggle::make('is_active')
                    ->label('Aktifkan Kupon')
                    ->required()
                    ->reactive(),
                Toggle::make('use_prefix')
                    ->label('Aktifkan Prefix')
                    ->required()
                    ->reactive()
                    ->disabled(fn($get) => !$get('is_active')),
                Slider::make('estimate')
                    ->label('Estimasi Peserta')
                    ->min(10)
                    ->max(1000000)
                    ->step(10)
                    ->disabled(fn($get) => !$get('is_active'))
                    ->helperText('Estimasi jumlah peserta antara 10 sampai 1.000.000'),
                TextArea::make('number_type')
                    ->label('Pilih Jenis Nomor')
                    ->options([
                        'urutan' => 'Angka Urut',
                        'acak' => 'Angka Acak',
                    ])
                    ->default('acak')
                    ->required()
                    ->disabled(fn($get) => !$get('is_active')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('form.name')
                    ->label('Form')
                    ->searchable(),
                BooleanColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
                TextColumn::make('prefix')
                    ->label('Prefix Kupon')
                    ->sortable(),
                TextColumn::make('estimate')
                    ->label('Estimasi Peserta')
                    ->sortable(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
