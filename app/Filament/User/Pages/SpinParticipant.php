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

        $this->spin();
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

        // Simpan ke tabel winners
        Winner::create([
            'form_id' => $this->form->id,
            'participant_id' => $available->id,
        ]);

        $this->winner = $available;

        Notification::make()
            ->title('Pemenang berhasil dipilih!')
            ->success()
            ->send();
    }
}
