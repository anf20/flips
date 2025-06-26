<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Flashcard dari JSON untuk Deck: ' . $deck->title) }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('flashcards.create_for_deck', ['deck' => $deck->id, 'expectedCount' => 1]) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-base font-medium">
                &larr; Kembali ke Form Biasa
            </a>
            <h3 class="text-2xl font-bold text-gray-800 text-center flex-grow">Tambah Flashcard dari JSON</h3>
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

        <form method="POST" action="{{ route('flashcards.store_from_json', $deck->id) }}" class="max-w-3xl mx-auto">
            @csrf

            <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                <p class="text-gray-700 mb-2">Masukkan data flashcard dalam format JSON. Setiap flashcard harus memiliki kunci `side_a` dan `side_b`.</p>
                <p class="text-gray-600 text-sm mb-4">Contoh format JSON:</p>
                <pre class="bg-gray-100 p-3 rounded-md text-gray-800 text-xs overflow-auto"><code>[
    {
        "side_a": "Pertanyaan 1",
        "side_b": "Jawaban 1"
    },
    {
        "side_a": "Pertanyaan 2",
        "side_b": "Jawaban 2"
    }
]</code></pre>
            </div>

            <div class="mb-6">
                <x-input-label for="json_data" :value="__('Data Flashcard (JSON)')" />
                <textarea id="json_data" name="json_data" rows="15"
                          class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm font-mono text-sm"
                          placeholder='[{"side_a": "Apa ibu kota Indonesia?", "side_b": "Jakarta"}]'
                          required>{{ old('json_data') }}</textarea>
                <x-input-error :messages="$errors->get('json_data')" class="mt-2" />
                <x-input-error :messages="$errors->get('json_parse_error')" class="mt-2" /> {{-- Untuk error parsing JSON --}}
                <x-input-error :messages="$errors->get('flashcards_data_error')" class="mt-2" /> {{-- Untuk error struktur data JSON --}}
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="bg-green-600 hover:bg-green-700 px-6 py-3 text-lg">
                    {{ __('Simpan Flashcard dari JSON') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
