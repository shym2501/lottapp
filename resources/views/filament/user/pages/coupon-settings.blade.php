<x-filament::page>
    <p>ID Form Anda: {{ $formId }}</p>

    {{ $this->form }}

    <x-filament::button wire:click="save" class="mt-4">
        Simpan Pengaturan
    </x-filament::button>

    @if (!empty($generatedCoupons))
        <div class="mt-6">
            <h2 class="text-lg font-bold mb-2">Preview Kupon:</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach ($generatedCoupons as $coupon)
                    <div class="border px-3 py-2 rounded text-center bg-dark shadow">
                        {{ $coupon }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-filament::page>
