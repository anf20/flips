<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Flashcard untuk Deck: ' . $deck->title) }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800 text-center flex-grow">
                Tambah Flashcard untuk Deck: {{ $deck->title }}
            </h3>
            {{-- Tombol untuk beralih ke form JSON --}}
            <a href="{{ route('flashcards.create_from_json', $deck->id) }}"
               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 text-base font-medium ml-4">
                Tambah dari JSON &rarr;
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('flashcards.store_for_deck', $deck->id) }}" class="max-w-2xl mx-auto">
            @csrf

            <div id="flashcard-inputs-container" class="space-y-6">
                @for ($i = 0; $i < $expectedCount; $i++)
                    <div class="p-4 border border-gray-200 rounded-md bg-gray-50">
                        <h5 class="font-semibold text-gray-700 mb-3">Flashcard #{{ $i + 1 }}</h5>
                        <div class="mb-3">
                            <label for="flashcards_{{ $i }}_side_a" class="block font-medium text-sm text-gray-700">Sisi A</label>
                            <x-text-input type="text" name="flashcards[{{ $i }}][side_a]" id="flashcards_{{ $i }}_side_a"
                                class="block w-full"
                                required value="{{ old('flashcards.' . $i . '.side_a') }}" />
                            <x-input-error :messages="$errors->get('flashcards.' . $i . '.side_a')" class="mt-2" />
                        </div>
                        <div>
                            <label for="flashcards_{{ $i }}_side_b" class="block font-medium text-sm text-gray-700">Sisi B</label>
                            <x-text-input type="text" name="flashcards[{{ $i }}][side_b]" id="flashcards_{{ $i }}_side_b"
                                class="block w-full"
                                required value="{{ old('flashcards.' . $i . '.side_b') }}" />
                            <x-input-error :messages="$errors->get('flashcards.' . $i . '.side_b')" class="mt-2" />
                        </div>
                    </div>
                @endfor
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-8 py-4 text-xl">
                    {{ __('Simpan Semua Flashcard') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
