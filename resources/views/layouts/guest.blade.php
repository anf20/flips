<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Flips - {{ config('app.name', 'Laravel') }}</title> <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="font-sans text-gray-900 antialiased">
    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-blue-600">Flips</div>
            <div>
                @if (Route::has('login'))
                    <div class="space-x-4"> @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-500 to-indigo-600">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>