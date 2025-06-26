<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Form Edit Kategori "{{ $collection->name }}"</h3>

        <form method="POST" action="{{ route('collections.update', $collection->id) }}" class="max-w-xl mx-auto">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <x-input-label for="name" :value="__('Nama Kategori')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $collection->name)" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mb-6">
                <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $collection->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end">
                <a href="{{ route('flashcards.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">Batal</a>
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 text-lg">
                    {{ __('Update Kategori') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>