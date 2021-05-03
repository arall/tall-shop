<div class="container px-6 mx-auto">
    <div class="my-10 text-gray-900 bg-white shadow-md lg:flex">

        <!-- Cart -->
        <div class="px-10 py-10 lg:w-3/4">
            @if(!empty($cart))
                <h3 class="text-lg font-medium leading-6">
                    {{ __('Shopping Cart') }}
                </h3>
                <div class="flex mt-10 mb-5 text-xs font-semibold text-gray-400 uppercase">
                    <h3 class="w-3/5">
                        {{ __('Product') }}
                    </h3>
                    <h3 class="w-1/5 text-center">
                        {{ __('Quantity') }}
                    </h3>
                    <h3 class="w-1/5 text-center">
                        {{ __('Price') }}
                    </h3>
                </div>
                @foreach ($cart as $hash => $item)
                    @php $product = App\Models\Product::find($item['product_id']); @endphp
                    <div class="flex items-center my-3">
                        <div class="flex w-3/5">
                            <div class="w-20">
                                <a href="{{ route('product', $product) }}">
                                    <img src="{{ $product->getCoverUrl('thumb') }}">
                                </a>
                            </div>
                            <div class="block ml-4">
                                <div>
                                    <span class="text-sm font-bold">
                                        <a href="{{ route('product', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </span>
                                </div>
                                @if (count($item['option_ids']))
                                    <div>
                                        @foreach ($item['option_ids'] as $optionId)
                                            @php $option = \App\Models\ProductVariantOption::find($optionId); @endphp
                                            <span class="text-xs text-gray-500">
                                                {{ $option->variant->name }}: {{ $option->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-center w-1/5 text-gray-600">
                            <div class="flex items-center mt-2">
                                <button wire:click="decrease('{{ $hash }}')" class="text-gray-500 focus:outline-none focus:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <span class="mx-2 text-gray-700">
                                    {{ $item['units'] }}
                                </span>
                                <button wire:click="increase('{{ $hash }}')" class="text-gray-500 focus:outline-none focus:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="w-1/5 text-sm text-center whitespace-nowrap">
                            <div class="font-semibold">
                                @price($product->getPrice($item['option_ids']) * $item['units'])
                            </div>
                            <div class="text-gray-500">
                                @price($product->getPrice($item['option_ids'])) each
                            </div>
                        </div>

                        <span class="text-sm font-semibold text-center">
                            <button class="float-right w-5" wire:click="remove('{{ $hash }}')"
                                title="{{ __('Remove') }}">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-gray-500 fill-current">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </span>
                    </div>
                @endforeach
                <div class="pt-5">
                    <div class="float-left">
                        <x-button wire:click="empty">
                            {{ __('Empty cart') }}
                        </x-button>
                    </div>
                    <div class="float-right">
                        <x-button.link href="{{ route('products') }}">
                            {{ __('Continue shopping') }}
                        </x-button.link>
                    </div>
                </div>
            @else
                <div>
                    {{ __('Your cart is empty') }}
                </div>
            @endif
        </div>

        <!-- Summary -->
        <div class="px-8 py-10 lg:w-1/4">
            <h3 class="text-lg font-medium leading-6">
                {{ __('Order Summary') }}
            </h3>

            <div class="mt-4">
                {{ __('Have a coupon?') }}
                <div class="flex mt-1 rounded-md shadow-sm">
                    <div class="relative flex items-stretch flex-grow focus-within:z-10">
                        <input type="text"
                            class="block w-full border-gray-300 rounded-none focus:ring-indigo-500 focus:border-indigo-500 rounded-l-md sm:text-sm">
                    </div>
                    <button
                        class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <span>{{ __('Apply') }}</span>
                    </button>
                </div>
            </div>
            @if(!empty($cart))
                <div class="py-6 mt-5 space-y-5 text-sm uppercase border-t">
                    @if(App\Helpers\Taxes::areEnabled())
                        <div class="flex justify-between">
                            <span>
                                {{ __('Taxes') }}
                            </span>
                            <span>
                                @price($totalTaxes)
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between font-semibold">
                        <span>
                            {{ __('Total Cost') }}
                        </span>
                        <span>
                            @price($totalPrice)
                        </span>
                    </div>
                </div>
                <div class="float-right">
                    <x-button.link-primary href="{{ route('checkout') }}" loading="true">
                        {{ __('Proceed to Checkout') }}
                    </x-button.link-primary>
                </div>
            @endif
        </div>

    </div>
</div>
