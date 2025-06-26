@extends('layouts.deck-management-layout') {{-- Menggunakan layout manajemen deck --}}

@section('content')

    <div class="flex justify-start mb-6">
        <a href="{{ route('decks.show', $flashcard->deck_id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out text-sm font-medium">
            &larr; Kembali ke Detail Deck
        </a>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Flashcard</h2>

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

    <form method="POST" action="{{ route('flashcards.update', $flashcard->id) }}" class="max-w-xl mx-auto">
        @csrf
        @method('PATCH') {{-- Menggunakan metode PATCH untuk update --}}

        <div class="mb-4">
            <x-input-label for="side_a" :value="__('Sisi A')" />
            <x-text-input id="side_a" class="block mt-1 w-full" type="text" name="side_a" :value="old('side_a', $flashcard->side_a)" required autofocus />
            <x-input-error :messages="$errors->get('side_a')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="side_b" :value="__('Sisi B')" />
            <x-text-input id="side_b" class="block mt-1 w-full" type="text" name="side_b" :value="old('side_b', $flashcard->side_b)" required />
            <x-input-error :messages="$errors->get('side_b')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <a href="{{ route('decks.show', $flashcard->deck_id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">Batal</a>
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 text-lg">
                {{ __('Update Flashcard') }}
            </x-primary-button>
        </div>
    </form>

@endsection