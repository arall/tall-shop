<a
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'py-2 px-4 border rounded-md text-sm leading-5 font-medium focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 border-indigo-600' . ($attributes->get('disabled') ? ' opacity-75 cursor-not-allowed' : ''),
    ]) }}
>
    {{ $slot }}
</a>
