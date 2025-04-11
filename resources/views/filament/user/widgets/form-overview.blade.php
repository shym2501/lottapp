<x-filament::widget>
    <x-filament::card>
        @php
            $form = $this->form();
        @endphp

        @if ($form)
            <div class="space-y-2">
                <h2 class="text-lg font-bold">📋 Informasi Form Anda</h2>

                <p><strong>Nama Form:</strong> {{ $form->title }}</p>
                <p><strong>Status:</strong>
                    <span @class([
                        'px-2 py-1 rounded text-white text-md',
                        'bg-green-500' => $form->status === 'paid',
                        'bg-yellow-500' => $form->status === 'pending',
                        'bg-gray-500' => $form->status === 'expired',
                    ])>
                        {{ ucfirst($form->status) }}
                    </span>
                </p>
                <p><strong>Mulai:</strong>
                    @if ($form->start_date)
                        {{ $form->start_date->format('d M Y') }}
                    @else
                        <span class="text-sm text-red-500">Lakukan pembayaran terlebih dahulu</span>
                    @endif
                </p>

                <p><strong>Berakhir:</strong>
                    @if ($form->end_date)
                        {{ $form->end_date->format('d M Y') }}
                    @else
                        <span class="text-sm text-red-500">Lakukan pembayaran terlebih dahulu</span>
                    @endif
                </p>


                @if ($form->status === 'paid')
                    <div class="flex gap-2 pt-4">
                        <a 
                            href="{{ route('form.show', $form->id) }}" 
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition"
                        >
                            🔍 Lihat Form
                        </a>

                        <button
                            onclick="navigator.clipboard.writeText('{{ route('form.show', $form->id) }}'); alert('Link berhasil disalin!')"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded hover:bg-orange-700 transition"
                        >
                            🔗 Salin Link
                        </button>
                    </div>
                @endif
            </div>
        @else
            <p>Belum ada form yang terdaftar.</p>
        @endif
    </x-filament::card>
</x-filament::widget>
