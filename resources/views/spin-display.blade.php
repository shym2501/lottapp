<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spin Display</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="/reverb/clients/reverb.js"></script> <!-- Laravel Reverb client -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body { margin: 0; background-color: #1a202c; color: white; }
    </style>
</head>
<body>
    <div class="text-center">
        <h1 class="text-4xl mb-4">🎉 Pemenang</h1>

        <div id="winner-info" class="space-y-2">
            <h2 id="winner-name" class="text-3xl font-bold"></h2>
            <p class="text-xl">Kode Kupon: <strong id="winner-code"></strong></p>
        </div>

        <div id="waiting-message" class="text-gray-400 mt-4">
            Menunggu hasil spin...
        </div>
    </div>

    <pre>
        Form ID: {{ $form->id ?? 'Form tidak tersedia' }}
    </pre>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.2.0/dist/web/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const winnerName = document.getElementById("winner-name");
            const winnerCode = document.getElementById("winner-code");
            const waitingMessage = document.getElementById("waiting-message");

            const formId = @json($form->id ?? null);

            const echo = new Echo({
                broadcaster: 'reverb',
                key: 'ehaactyjmz5az9c7989b',
                wsHost: window.location.hostname,
                wsPort: 8080,
                wssPort: 8080,
                forceTLS: false,
                disableStats: true,
            });

            echo.channel(`spin.display.${formId}`)
            .listen('.SpinUpdated', (e) => {
                console.log(e.data);  // Periksa data yang diterima
                waitingMessage.style.display = 'none';
                winnerName.textContent = e.data.winner.name;
                winnerCode.textContent = e.data.winner.kode_kupon ?? '-';
            });

        });
    </script>


</body>
</html>
