<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Daftar Akun Baru</h2>

    <div class="mb-4 text-sm text-gray-600 text-center">
        Daftar dan mulai kelola flashcard Anda sekarang!
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-blue-600 hover:text-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-blue-600 hover:bg-blue-700 px-6 py-3 text-lg"> {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Sudah punya akun?
                <a href="{{ route('login') }}" class="underline text-blue-600 hover:text-blue-800">Login di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>