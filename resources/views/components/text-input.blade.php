@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => '
        block
        w-full
        px-4 py-2
        text-base
        text-gray-700
        bg-white
        border border-gray-300
        rounded-md
        shadow-sm
        transition
        duration-300
        ease-in-out
        focus:border-blue-500
        focus:ring-2
        focus:ring-blue-500
        focus:ring-opacity-50
        focus:outline-none
        placeholder-gray-400
        disabled:bg-gray-100
        disabled:cursor-not-allowed
    '
]) !!}>