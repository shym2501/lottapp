<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-2xl font-bold">Undian Peserta</h2>

        @if ($winner)
            <div class="p-6 rounded-xl bg-green-100 text-center">
                <h3 class="text-xl font-semibold">🎉 Calon Pemenang:</h3>
                <p class="text-2xl">{{ $winner->name }}</p>
                @if ($winner->kode_kupon)
                    <p class="text-lg text-gray-700">Kode Kupon: <strong>{{ $winner->kode_kupon }}</strong></p>
                @endif

                <div class="mt-4 flex justify-center gap-4">
                    <x-filament::button wire:click="confirmWinner" color="success">
                        Simpan Pemenang ✅
                    </x-filament::button>

                    <x-filament::button wire:click="$set('winner', null)" color="secondary">
                        Batalkan ❌
                    </x-filament::button>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="spin">
            <x-filament::button type="submit">
                Spin Lagi 🎯
            </x-filament::button>
        </form>
    </div>
</x-filament::page>
