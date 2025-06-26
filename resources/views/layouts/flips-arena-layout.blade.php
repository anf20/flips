<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Flips Arena - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Anda bisa memindahkan CSS ini ke resources/css/app.css dan menjalankan npm run dev untuk produksi */
        .flip-card {
            background-color: transparent;
            perspective: 1000px; /* For 3D effect */
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            border-radius: 0.5rem; /* Match Tailwind's rounded-lg */
        }

        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; /* Hide the back of the card during flip */
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .flip-card-front {
            background-color: #f0f9ff; /* light blue-50 */
            color: #1f2937; /* gray-800 */
        }

        .flip-card-back {
            background-color: #dbeafe; /* light blue-200 */
            color: #1f2937;
            transform: rotateY(180deg);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 to-indigo-600 font-sans antialiased flex items-center justify-center min-h-screen">
    <div class="w-full max-w-6xl bg-white p-8 rounded-lg shadow-xl text-center"> {{-- Mengubah max-w-2xl menjadi max-w-6xl --}}
        @yield('content') {{-- Mengubah ini dari {{ $slot }} --}}
    </div>

    @stack('scripts')
</body>
</html>
