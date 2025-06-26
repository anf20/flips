<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flips - Aplikasi Flashcard Terbaik Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100 font-sans text-gray-900 antialiased">

    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-blue-600">Flips</div>
            <div>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-medium px-4 py-2 rounded-md transition duration-300 ease-in-out">Register</a>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-20 text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 leading-tight">
                Kuasi Pengetahuan dengan <span class="block mt-2">Flips!</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto">
                Metode flashcard interaktif untuk menghafal kosakata, rumus, fakta, dan apa pun yang Anda inginkan.
            </p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-100 px-8 py-3 rounded-full text-lg font-semibold shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                Mulai Sekarang!
            </a>
        </div>
    </header>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Kenapa Memilih Flips?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl text-blue-500 mb-4">📚</div>
                    <h3 class="text-2xl font-semibold mb-3 text-gray-800">Fleksibilitas Tanpa Batas</h3>
                    <p class="text-gray-600">Buat kartu dengan konten apa pun yang Anda inginkan. Dari bahasa, sains, hingga fakta unik.</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl text-green-500 mb-4">📂</div>
                    <h3 class="text-2xl font-semibold mb-3 text-gray-800">Organisasi Mudah</h3>
                    <p class="text-gray-600">Susun kartu Anda ke dalam kategori kustom untuk manajemen pembelajaran yang rapi.</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl text-purple-500 mb-4">🧠</div>
                    <h3 class="text-2xl font-semibold mb-3 text-gray-800">Metode Hafalan Efektif</h3>
                    <p class="text-gray-600">Manfaatkan kekuatan visual dan pengulangan untuk daya ingat yang lebih kuat.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-700 text-white py-16 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-6">Siap Menguasai Materi Anda?</h2>
            <p class="text-xl mb-8 max-w-xl mx-auto">Bergabunglah dengan ribuan pengguna Flips dan tingkatkan kemampuan hafalan Anda hari ini!</p>
            <a href="{{ route('register') }}" class="bg-white text-blue-700 hover:bg-blue-100 px-10 py-4 rounded-full text-xl font-semibold shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                Daftar Gratis Sekarang!
            </a>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8 text-center">
        <div class="container mx-auto px-4">
            <p>&copy; 2025 Flips. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>