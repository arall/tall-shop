<div class="container mx-auto px-6">
    <div class="lg:flex shadow-md my-10 bg-white text-gray-900">

        <!-- Cart -->
        <div class="lg:w-3/4 px-10 py-10">
            @if(!empty($cart))
                <h3 class="text-lg leading-6 font-medium">
                    {{ __('Shopping Cart') }}
                </h3>
                <div class="flex mt-10 mb-5 text-gray-400 font-semibold text-xs uppercase">
                    <h3 class="w-3/5">
                        {{ __('Product') }}
                    </h3>
                    <h3 class="text-center w-1/5">
                        {{ __('Quantity') }}
                    </h3>
                    <h3 class="text-center w-1/5">
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
                                    <span class="font-bold text-sm">
                                        <a href="{{ route('product', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </span>
                                </div>
                                @if ($product->category)
                                    <span class="text-xs text-gray-500">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
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
                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <span class="text-gray-700 mx-2">
                                    {{ $item['units'] }}
                                </span>
                                <button wire:click="increase('{{ $hash }}')" class="text-gray-500 focus:outline-none focus:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="text-center w-1/5 text-sm whitespace-nowrap">
                            <div class="font-semibold">
                                @price($product->getPrice($item['option_ids']) * $item['units'])
                            </div>
                            <div class="text-gray-500">
                                @price($product->getPrice($item['option_ids'])) each
                            </div>
                        </div>

                        <span class="text-center font-semibold text-sm">
                            <button class="w-5 float-right" wire:click="remove('{{ $hash }}')"
                                title="{{ __('Remove') }}">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="fill-current text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </span>
                    </div>
                @endforeach
                <div class="pt-5">
                    <div class="float-left">
                        <x-button>
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
        <div class="lg:w-1/4 px-8 py-10">
            <h3 class="text-lg leading-6 font-medium">
                {{ __('Order Summary') }}
            </h3>

            <div class="mt-4">
                {{ __('Have a coupon?') }}
                <div class="mt-1 flex rounded-md shadow-sm">
                    <div class="relative flex items-stretch flex-grow focus-within:z-10">
                        <input type="text"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300">
                    </div>
                    <button
                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <span>{{ __('Apply') }}</span>
                    </button>
                </div>
            </div>
            <div class="border-t mt-5 flex font-semibold justify-between py-6 text-sm uppercase">
                <span>
                    {{ __('Total Cost') }}
                </span>
                <span>
                    @price($totalPrice)
                </span>
            </div>
            <div class="float-right">
                <x-button.link-primary href="{{ route('checkout') }}" loading="true">
                    {{ __('Proceed to Checkout') }}
                </x-button.link-primary>
            </div>
        </div>

    </div>
</div>
