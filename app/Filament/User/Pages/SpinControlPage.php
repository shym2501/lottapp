<?php

namespace App\Filament\User\Pages;

use App\Events\SpinResultGenerated;
use App\Models\Form;
use App\Models\Participant;
use App\Models\Winner;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SpinControlPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationGroup = 'Manajemen Undian';
    protected static string $view = 'filament.user.pages.spin-control-page';
    protected static ?string $title = 'Kontrol Spin';

    public ?Form $form = null;
    public ?Participant $winner = null;
    public int $count = 0;

    public function mount(): void
    {
        $this->form = Form::where('user_id', Auth::id())->first();
        $this->count = Winner::where('form_id', $this->form->id)->count();
    }

    public function spin(): void
    {
        $available = Participant::where('form_id', $this->form->id)
            ->whereNotIn('id', function ($query) {
                $query->select('participant_id')->from('winners')->where('form_id', $this->form->id);
            })
            ->inRandomOrder()
            ->first();

        if (!$available) {
            Notification::make()->title('Semua peserta sudah menang.')->warning()->send();
            return;
        }

        $winner = Winner::create([
            'form_id' => $this->form->id,
            'participant_id' => $available->id,
        ]);

        $this->winner = $available;
        $this->count++;

        broadcast(new SpinResultGenerated($available))->toOthers();

        Notification::make()->title('Pemenang berhasil dipilih!')->success()->send();
    }
}
