<?php

namespace App\Filament\User\Pages;

use App\Events\SpinStarted;
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
    public int $spinCount = 0;
    public bool $allParticipantsWon = false;

    public function mount(): void
    {
        $this->form = Form::where('user_id', Auth::id())->first();
        $this->spinCount = 0;
        $this->allParticipantsWon = false;  // Setel status awal

        if (!$this->form) {
            Notification::make()
                ->title('Form tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        // Cek apakah semua peserta sudah menang
        $this->checkIfAllParticipantsWon();
    }

    // Cek apakah semua peserta sudah menang
    public function checkIfAllParticipantsWon(): void
    {
        $totalParticipants = Participant::where('form_id', $this->form->id)->count();
        $totalWinners = Winner::where('form_id', $this->form->id)->count();

        // Jika jumlah pemenang sama dengan jumlah peserta, berarti semua peserta sudah menang
        $this->allParticipantsWon = $totalParticipants === $totalWinners;
    }

    public function spin(): void
    {
        // Jika semua peserta sudah menang, tidak boleh spin lagi
        if ($this->allParticipantsWon) {
            Notification::make()
                ->title('Semua peserta sudah menang!')
                ->warning()
                ->send();
            return;
        }

        // Mengupdate spin count
        $this->spinCount++;

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
                ->title('Semua peserta sudah menang!')
                ->warning()
                ->send();
            return;
        }

        // Simpan pemenang ke tabel winners
        Winner::create([
            'form_id' => $this->form->id,
            'participant_id' => $available->id,
        ]);

        $this->winner = $available;

        // Cek jika semua peserta sudah menang setelah menambahkan pemenang baru
        $this->checkIfAllParticipantsWon();

        Notification::make()
            ->title('Pemenang berhasil dipilih!')
            ->success()
            ->send();
    }
}
