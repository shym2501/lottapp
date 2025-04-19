<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-2xl font-bold">Kontrol Spin</h2>

        <div class="text-lg">Total Spin: {{ $count }}</div>

        <form wire:submit.prevent="spin">
            <x-filament::button type="submit">🎯 SPIN</x-filament::button>
        </form>

        @if ($winner)
            <div class="p-6 bg-green-100 rounded-xl mt-4">
                <h3 class="text-xl font-semibold">🎉 Pemenang:</h3>
                <p class="text-2xl">{{ $winner->name }}</p>
                @if ($winner->kode_kupon)
                    <p>Kode Kupon: <strong>{{ $winner->kode_kupon }}</strong></p>
                @endif
            </div>
        @endif

        <a href="{{ route('filament.user.pages.spin-display-page') }}" target="_blank">
            <x-filament::button color="secondary">🖥️ Buka Halaman Display</x-filament::button>
        </a>
    </div>
</x-filament::page>
