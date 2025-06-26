<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang Kembali di Flips!</h3>
        <p class="text-gray-700 mb-6">Anda telah berhasil masuk.</p>
        
        {{-- Bagian untuk menampilkan statistik --}}
        <div class="mt-8 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                <h4 class="text-xl font-semibold text-blue-800 mb-2">Total Flashcard</h4>
                <p class="text-4xl font-bold text-blue-600">{{ $totalFlashcards ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-6 rounded-lg shadow-md">
                <h4 class="text-xl font-semibold text-green-800 mb-2">Total Deck</h4>
                <p class="text-4xl font-bold text-green-600">{{ $totalDecks ?? 0 }}</p>
            </div>
            <div class="bg-purple-100 p-6 rounded-lg shadow-md">
                <h4 class="text-xl font-semibold text-purple-800 mb-2">Total Kategori</h4>
                <p class="text-4xl font-bold text-purple-600">{{ $totalCollections ?? 0 }}</p>
            </div>
        </div>
        {{-- Akhir bagian statistik --}}

        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6"> {{-- Tambah mt-6 untuk jarak dari statistik --}}
            <a href="{{ route('flashcards.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition duration-300">
                Pergi ke Kartu Saya
            </a>
            <a href="{{ route('decks.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-lg font-semibold transition duration-300">
                Buat Deck Baru
            </a>
        </div>
    </div>
</x-app-layout>
