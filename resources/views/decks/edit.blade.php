@extends('layouts.minimal-layout') {{-- Menggunakan minimal-layout --}}

@section('content')

    <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Deck "{{ $deck->title }}"</h2>

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

    <form method="POST" action="{{ route('decks.update', $deck->id) }}" class="max-w-xl mx-auto">
        @csrf
        @method('PATCH') {{-- Menggunakan metode PATCH untuk update --}}

        <!-- Judul Deck -->
        <div class="mb-4">
            <x-input-label for="title" :value="__('Judul Deck')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $deck->title)" required autofocus />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <!-- Deskripsi (Opsional) -->
        <div class="mb-4">
            <x-input-label for="description" :value="__('Deskripsi Deck (Opsional)')" />
            <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $deck->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <!-- Pilih Kategori (Opsional) -->
        <div class="mb-6">
            <x-input-label for="collection_id" :value="__('Pilih Kategori (Opsional)')" />
            <select id="collection_id" name="collection_id" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                <option value="">-- Pilih Kategori --</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ old('collection_id', $deck->collection_id) == $collection->id ? 'selected' : '' }}>
                        {{ $collection->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">Jika tidak dipilih, akan masuk ke kategori "Baru Ditambahkan".</p>
            <x-input-error :messages="$errors->get('collection_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <a href="{{ route('decks.show', $deck->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">Batal</a>
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 text-lg">
                {{ __('Update Deck') }}
            </x-primary-button>
        </div>
    </form>

@endsection