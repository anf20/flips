<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Manajemen Deck & Flashcard - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-web@2.6.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujlZH0+YF7m8Q6oD8w8v+pM0/g+k4T8E/A/oN/fJqFjFfLw8Lw8/g+k4T8E/A/oN/fJqFjFfLw8Lw8/g+" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gradient-to-br from-blue-500 to-indigo-600 font-sans antialiased flex flex-col items-center justify-center min-h-screen p-8">
    <div class="w-full max-w-6xl bg-white p-8 rounded-lg shadow-xl">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>