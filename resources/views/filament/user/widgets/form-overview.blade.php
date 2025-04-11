<x-filament::widget>
    <x-filament::card>
        @php
            $form = $this->form();
        @endphp

        @if ($form)
            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <h2 class="text-xl font-bold">📋 Formulir Anda</h2>
                    <p><strong>Nama:</strong> {{ $form->title }}</p>
                    <p><strong>Status:</strong>
                        <span @class([
                            'px-2 py-1 rounded text-white text-sm',
                            'bg-green-500' => $form->status === 'paid',
                            'bg-yellow-500' => $form->status === 'pending',
                            'bg-gray-500' => $form->status === 'expired',
                        ])>
                            {{ ucfirst($form->status) }}
                        </span>
                    </p>
                    <p><strong>Mulai:</strong> {{ $form->start_date ? \Carbon\Carbon::parse($form->start_date)->format('d M Y') : 'Belum Aktif' }}</p>
                    <p><strong>Berakhir:</strong> {{ $form->end_date ? \Carbon\Carbon::parse($form->end_date)->format('d M Y') : 'Belum Aktif' }}</p>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    @if ($form->status !== 'paid')
                        <div class="text-center text-sm text-gray-700">
                            <p class="mb-2">🔒 Formulir belum aktif.</p>
                            <p>Silakan selesaikan pembayaran untuk mengaktifkan dan membagikan link.</p>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">📢 Bagikan link formulir:</p>
                            <input type="text" value="{{ url('/form/'.$form->id) }}" readonly
                                class="w-full text-sm bg-white border rounded px-2 py-1" />
                        </div>
                    @endif
                </div>
            </div>
        @else
            <p class="text-gray-600">Anda belum memiliki formulir.</p>
        @endif
    </x-filament::card>
</x-filament::widget>
