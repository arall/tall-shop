@props([
    'href',
    'title',
])

<a href="{{ $href }}"
    class="hover:bg-white group rounded-md px-3 py-2 flex items-center text-sm font-medium
    @if(url()->current() == $href)
        {{ 'text-indigo-600 bg-indigo-50' }}
    @else
        {{ 'text-gray-900 hover:text-gray-900' }}
    @endif
    ">
    {{ $slot }}
</a>
