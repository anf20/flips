<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Daftar Kategori') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Kategori Flashcard Anda</h3>

        <div class="flex justify-end mb-4">
            <a href="{{ route('collections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                + Tambah Kategori Baru
            </a>
        </div>

        @if ($collections->isEmpty())
            <div class="border border-gray-200 rounded-md p-4 bg-gray-50 text-gray-600 text-center">
                <p>Anda belum memiliki kategori apa pun.</p>
                <p class="mt-2 text-sm">Klik "Tambah Kategori Baru" di atas untuk mulai mengelompokkan deck Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($collections as $collection)
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <h4 class="font-semibold text-xl text-gray-800 mb-2">{{ $collection->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($collection->description, 100) ?: 'Tidak ada deskripsi.' }}</p>
                        <div class="flex justify-end space-x-2">
                            {{-- Anda bisa menambahkan link untuk melihat deck di kategori ini --}}
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Deck</a>
                            <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>