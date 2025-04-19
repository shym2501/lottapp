<?php

namespace App\Filament\User\Pages;

use App\Models\Form;
use App\Models\Participant;
use App\Models\Winner;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SpinParticipant extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Undian';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Metode Spin';
    protected static string $view = 'filament.user.pages.spin-participant';

    public ?Form $form = null;
    public ?Participant $winner = null;

    public function mount(): void
    {
        $this->form = Form::where('user_id', Auth::id())->first();

        if (!$this->form) {
            $this->notify('danger', 'Form tidak ditemukan');
            return;
        }

        // Jangan panggil spin() otomatis di sini
    }

    public function spin(): void
    {
        // Ambil peserta yang belum menang
        $available = Participant::where('form_id', $this->form->id)
            ->whereNotIn('id', function ($query) {
                $query
                    ->select('participant_id')
                    ->from('winners')
                    ->where('form_id', $this->form->id);
            })
            ->inRandomOrder()
            ->first();

        if (!$available) {
            Notification::make()
                ->title('Semua peserta sudah menang.')
                ->warning()
                ->send();
            return;
        }

        // Tampilkan calon pemenang (belum disimpan)
        $this->winner = $available;
    }

    public function confirmWinner(): void
    {
        if (!$this->winner) {
            Notification::make()
                ->title('Tidak ada peserta yang dipilih.')
                ->warning()
                ->send();
            return;
        }

        Winner::create([
            'form_id' => $this->form->id,
            'participant_id' => $this->winner->id,
        ]);

        Notification::make()
            ->title('Pemenang berhasil disimpan!')
            ->success()
            ->send();

        // Kosongkan pemenang setelah disimpan (opsional)
        $this->winner = null;
    }
}
