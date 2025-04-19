<x-filament::page>
    <div class="space-y-4">
        {{-- Bagian Header Konten --}}
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Undian Peserta</h2>

            <a href="{{ route('spin-display', ['form' => $form->id]) }}" target="_blank">
                <x-filament::button color="gray">
                    Layar Kedua 📺
                </x-filament::button>
            </a>
        </div>

        @if ($winner)
            <div class="p-6 rounded-xl bg-green-100 text-center">
                <h3 class="text-xl font-semibold">🎉 Pemenang:</h3>
                <p class="text-2xl">{{ $winner->name }}</p>
                @if ($winner->kode_kupon)
                    <p class="text-lg text-gray-700">
                        Kode Kupon: <strong>{{ $winner->kode_kupon }}</strong>
                    </p>
                @endif
            </div>
        @endif

        <div class="flex justify-between items-center">
            <span>Spin ke: <strong>{{ $spinCount }}</strong></span>

            {{-- Tombol Spin --}}
            <form wire:submit.prevent="spin">
                <x-filament::button type="submit" :disabled="$allParticipantsWon">
                    Spin 🎯
                </x-filament::button>
            </form>
        </div>

        @if ($allParticipantsWon)
            <div class="mt-4 text-center text-lg text-red-600">
                <p>Semua peserta sudah menang! Tidak dapat melakukan spin lagi.</p>
            </div>
        @endif
    </div>
</x-filament::page>
