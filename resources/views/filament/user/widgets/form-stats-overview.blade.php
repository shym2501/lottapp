<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-indigo-500 text-white rounded-lg p-4 shadow">
                <h3 class="text-sm font-semibold">👥 Jumlah Peserta</h3>
                <p class="text-2xl font-bold mt-1">{{ $this->getParticipantCount() }}</p>
            </div>

            <div class="bg-orange-500 text-white rounded-lg p-4 shadow">
                <h3 class="text-sm font-semibold">📅 Sisa Hari Aktif</h3>
                <p class="text-2xl font-bold mt-1">
                    @if ($this->getDaysLeft() !== null)
                        {{ $this->getDaysLeft() }} Hari lagi
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
