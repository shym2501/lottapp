<?php

namespace App\Filament\User\Pages;

use App\Models\Coupon;
use App\Models\Form;
use Filament\Forms\Components\{Toggle, TextInput, Select, Section, Radio};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form as FormsForm;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Manajemen Umum';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.user.pages.coupon-settings';
    protected static ?string $title = 'Pengaturan Kupon';

    public ?string $formId = null;
    public array $data = [];
    public array $generatedCoupons = [];

    public function mount(): void
    {
        // Ambil form berdasarkan user yang sedang login
        $form = Form::where('user_id', auth()->id())->firstOrFail();
        $this->formId = (string) $form->id;

        // Ambil data kupon yang ada atau buat baru jika tidak ada
        $coupon = Coupon::firstOrNew(['form_id' => $this->formId]);

        // Isi data default jika kupon belum ada
        $this->fill([
            'data' => [
                'is_active' => $coupon->is_active,
                'use_prefix' => $coupon->use_prefix,
                'prefix' => $coupon->prefix,
                'number_type' => $coupon->number_type,
                'estimasi_peserta' => $coupon->estimasi_peserta,
            ]
        ]);
    }

    public function form(FormsForm $form): FormsForm
    {
        return $form
            ->schema([
                Toggle::make('is_active')
                    ->label('Aktifkan Fitur Kupon')
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            // Jika diaktifkan, tampilkan bagian tambahan untuk pengaturan kupon
                            $this->data['is_active'] = true;
                        } else {
                            $this->data['is_active'] = false;
                        }
                    }),
                Section::make([
                    Toggle::make('use_prefix')
                        ->label('Gunakan Prefix'),
                    TextInput::make('prefix')
                        ->label('Prefix')
                        ->maxLength(10)
                        ->required(fn($get) => $get('use_prefix')),
                    Select::make('number_type')
                        ->label('Tipe Nomor Kupon')
                        ->options([
                            'sequential' => 'Nomor Urut',
                            'random' => 'Nomor Acak',
                        ]),
                    Radio::make('estimasi_peserta')
                        ->label('Estimasi Peserta')
                        ->options([
                            '10' => 'Puluhan',
                            '100' => 'Ratusan',
                            '1000' => 'Ribuan',
                            '10000' => 'Puluhan Ribu',
                            '100000' => 'Ratusan Ribu',
                        ])
                        ->inlineLabel(false)
                        ->required(),
                ])
                    ->visible(fn($get) => $get('is_active'))  // Hanya tampil jika fitur kupon aktif
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->form->validate();

        $this->data['use_prefix'] = (bool) ($this->data['use_prefix'] ?? false);

        $form = Form::where('id', $this->formId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$form) {
            Notification::make()
                ->title('Form tidak ditemukan atau tidak valid.')
                ->danger()
                ->send();
            return;
        }

        // Simpan data kupon
        Coupon::updateOrCreate(
            ['form_id' => $this->formId],
            $this->data
        );

        // Generate kupon jika aktif
        if ($this->data['is_active']) {
            $this->generatedCoupons = $this->generateCoupons();
        } else {
            $this->generatedCoupons = [];
        }

        Notification::make()
            ->title('Pengaturan kupon berhasil disimpan.')
            ->success()
            ->send();
    }

    public function generateCoupons(): array
    {
        $prefix = ($this->data['use_prefix'] ?? false) ? ($this->data['prefix'] ?? '') . '-' : '';
        $estimasi = (int) ($this->data['estimasi_peserta'] ?? 100);
        $numberType = $this->data['number_type'] ?? 'sequential';

        $digitLength = strlen((string) $estimasi);
        $coupons = [];

        for ($i = 1; $i <= $estimasi; $i++) {
            if ($numberType === 'sequential') {
                $number = str_pad($i, $digitLength, '0', STR_PAD_LEFT);
            } else {
                $number = strtoupper(Str::random(6));
            }

            $coupons[] = $prefix . $number;
        }

        return $coupons;
    }
}
