<x-filament::page>
    <div class="flex items-center justify-center h-screen bg-black text-white">
        <div x-data="{ winner: null }"
             x-init="
                Echo.channel('spin-channel')
                    .listen('.spin-result', (e) => {
                        winner = e.participant;
                    });
             "
             class="text-center text-4xl">
            <template x-if="winner">
                <div>
                    🎉 <span x-text=\"winner.name\"></span><br>
                    <span x-show=\"winner.kode_kupon\" class=\"text-xl\">Kode Kupon: <span x-text=\"winner.kode_kupon\"></span></span>
                </div>
            </template>
            <template x-if=\"!winner\">
                <div>Menunggu hasil spin...</div>
            </template>
        </div>
    </div>
</x-filament::page>
