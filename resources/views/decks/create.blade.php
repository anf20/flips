<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Deck Baru') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Informasi Deck (Judul)</h3>

        <form method="POST" action="{{ route('decks.store') }}" class="max-w-xl mx-auto">
            @csrf

            <div class="mb-4">
                <x-input-label for="title" :value="__('Judul Deck')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="description" :value="__('Deskripsi Deck (Opsional)')" />
                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="collection_id" :value="__('Pilih Kategori (Opsional)')" />
                <select id="collection_id" name="collection_id" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection->id }}" {{ old('collection_id') == $collection->id ? 'selected' : '' }}>
                            {{ $collection->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Jika tidak dipilih, akan masuk ke kategori "Baru Ditambahkan".</p>
                <x-input-error :messages="$errors->get('collection_id')" class="mt-2" />
            </div>

            <div class="mb-6">
                <x-input-label for="expected_flashcards_count" :value="__('Jumlah Flashcard yang Akan Ditambahkan')" />
                <x-text-input id="expected_flashcards_count" class="block mt-1 w-full" type="number" name="expected_flashcards_count" value="{{ old('expected_flashcards_count', 10) }}" min="1" max="100" />
                <x-input-error :messages="$errors->get('expected_flashcards_count')" class="mt-2" />
                <p class="text-sm text-gray-500 mt-1">Anda akan mengisi flashcard ini di halaman selanjutnya.</p>
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 text-lg">
                    {{ __('Simpan Deck & Lanjut') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>