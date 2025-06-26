<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kartu Saya') }}
        </h2>
    </x-slot>

     <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Kelola Deck & Kategori Anda</h3>

        {{-- Top action buttons: Tambah Deck & Tambah Kategori --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a href="{{ route('decks.create') }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-md transition duration-300 text-lg">
                + Tambah Deck
            </a>
            <a href="{{ route('collections.create') }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-md transition duration-300 text-lg">
                + Tambah Kategori
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search Form --}}
        <div class="mb-8 p-4 bg-white rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('flashcards.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
                <input type="text" name="q" placeholder="Cari deck, kategori, atau flashcard..."
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                       value="{{ request('q') }}">
                
                <select name="type" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">

                    <option value="deck" {{ request('type') == 'deck' ? 'selected' : '' }}>Deck</option>
                    <option value="kategori" {{ request('type') == 'kategori' ? 'selected' : '' }}>Kategori</option>
            
                </select>
                
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 font-medium">
                    Cari
                </button>
                @if(request('q') || request('type'))
                    <a href="{{ route('flashcards.index') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50 font-medium text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>
        {{-- End Search Form --}}

        <div class="space-y-10">
            {{-- Conditional rendering based on search results vs. no content --}}
            @if ($collections->isEmpty() && $uncategorizedDecks->isEmpty())
                @if (request('q') || request('type'))
                    <div class="text-center p-6 bg-white rounded-lg shadow-md border border-gray-200">
                        <p class="text-gray-700 text-lg mb-4">Tidak ditemukan hasil untuk pencarian "{{ request('q') }}" di tipe {{ request('type') ?: 'Semua Tipe' }}.</p>
                        <a href="{{ route('flashcards.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-lg font-semibold transition duration-300">
                            Reset Pencarian
                        </a>
                    </div>
                @else
                    <div class="border border-gray-200 rounded-md p-6 bg-gray-50 text-gray-600 text-center">
                        <p class="mb-2">Anda belum memiliki deck atau kategori apa pun.</p>
                        <p class="text-sm">Mulai dengan "Tambah Deck" atau "Tambah Kategori" di atas.</p>
                    </div>
                @endif
            @else
                {{-- Uncategorized Decks Section --}}
                @if ($uncategorizedDecks->isNotEmpty())
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">Deck Tanpa Kategori</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($uncategorizedDecks as $deck)
                                {{-- Link ke halaman Detail Deck --}}
                                <a href="{{ route('decks.show', $deck->id) }}" class="group">
                                    <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 h-full flex flex-col transition-all duration-200 group-hover:shadow-lg group-hover:border-blue-500">
                                        <h5 class="font-semibold text-lg text-gray-800 mb-1 group-hover:text-blue-600">{{ $deck->title }}</h5>
                                        <p class="text-gray-600 text-sm mb-3 flex-grow">{{ Str::limit($deck->description, 70) ?: 'Tidak ada deskripsi.' }}</p>
                                        <span class="mt-auto text-blue-500 group-hover:underline text-sm font-medium">Lihat Detail &rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Collections (Categories) Section --}}
                @foreach ($collections as $collection)
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4"> {{-- Container untuk judul dan tombol --}}
                            <h4 class="text-xl font-bold text-gray-800">{{ $collection->name }}</h4>
                            <div class="flex space-x-2"> {{-- Tombol Edit & Hapus Kategori --}}
                                <a href="{{ route('collections.edit', $collection->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600 transition-colors duration-200">Edit</a>
                                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Deck yang terkait akan menjadi \'Tanpa Kategori\'.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition-colors duration-200 focus:outline-none">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if ($collection->decks->isEmpty())
                                <p class="text-gray-600 col-span-full p-4 bg-gray-50 rounded-md border border-gray-200">Belum ada deck di kategori ini.</p>
                            @else
                                @foreach ($collection->decks as $deck)
                                    {{-- Link ke halaman Detail Deck --}}
                                    <a href="{{ route('decks.show', $deck->id) }}" class="group">
                                        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 h-full flex flex-col transition-all duration-200 group-hover:shadow-lg group-hover:border-blue-500">
                                            <h5 class="font-semibold text-lg text-gray-800 mb-1 group-hover:text-blue-600">{{ $deck->title }}</h5>
                                            <p class="text-gray-600 text-sm mb-3 flex-grow">{{ Str::limit($deck->description, 70) ?: 'Tidak ada deskripsi.' }}</p>
                                            <span class="mt-auto text-blue-500 group-hover:underline text-sm font-medium">Lihat Detail &rarr;</span>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>