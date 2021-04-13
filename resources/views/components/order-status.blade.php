@props(['order'])

<span
    class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full
    @if(in_array($order->status, [0, 6]))
        {{ 'text-yellow-800 bg-yellow-100' }}
    @elseif(in_array($order->status, [1, 2]))
        {{ 'text-blue-800 bg-blue-100' }}
    @elseif(in_array($order->status, [3, 4]))
        {{  'text-green-800 bg-green-100' }}
    @else
        {{  'text-red-800 bg-red-100' }}
    @endif
    ">
    {{ __($order->getStatus()) }}
</span>
