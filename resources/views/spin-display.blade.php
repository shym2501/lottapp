<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin Display</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }
        .spin-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f8f8;
        }
        .spin-wheel {
            width: 300px;
            height: 300px;
            background: #fff;
            border-radius: 50%;
            border: 10px solid #e3e3e3;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .spin-wheel .spinner {
            position: absolute;
            width: 20px;
            height: 50px;
            background: #333;
            border-radius: 50%;
            transform-origin: center;
        }
        .spin-wheel .spinner.spin-animation {
            animation: spin 5s linear infinite;
        }
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="spin-container">
        <div class="spin-wheel">
            <div class="spinner"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        window.Echo.channel('spin-display.' + @json($userId))
            .listen('.spin.started', (event) => {
                const participant = event.participant;
                console.log('Pemenang:', participant.name, participant.kode_kupon);

                // Simulasi animasi spin
                document.querySelector('.spin-wheel .spinner').classList.add('spin-animation');
            });
    </script>
</body>
</html>
