<main class="max-w-2xl px-4 mx-auto sm:px-6 lg:max-w-7xl lg:px-8">
    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
        {{ __('Shopping Cart') }}
    </h1>

    <form class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
        <section aria-labelledby="cart-heading" class="lg:col-span-7">
            <h2 id="cart-heading" class="sr-only">
                {{ __('Items in your shopping cart') }}
            </h2>

            @if (!empty($cart))
                <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    @foreach ($cart as $hash => $item)
                        @php $product = App\Models\Product::find($item['product_id']); @endphp
                        <li class="flex py-6 sm:py-10">
                            <div class="flex-shrink-0">
                                <img src="{{ $product->getCoverUrl('thumb') }}" alt="{{ $product->name }}"
                                    class="object-cover object-center w-24 h-24 rounded-md sm:w-48 sm:h-48">
                            </div>

                            <div class="flex flex-col justify-between flex-1 ml-4 sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm">
                                                <a href="{{ route('product', $product) }}"
                                                    class="font-medium text-gray-700 hover:text-gray-800">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                        </div>
                                        <div class="flex mt-1 text-sm">
                                            @if (count($item['option_ids']))
                                                <div>
                                                    @foreach ($item['option_ids'] as $optionId)
                                                        @php $option = \App\Models\ProductVariantOption::find($optionId); @endphp
                                                        <p class="text-gray-500">
                                                            {{ $option->variant->name }}: {{ $option->name }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            @price($product->getPrice($item['option_ids']) * $item['quantity'])
                                        </p>
                                        @if ($item['quantity'])
                                            <p class="text-sm italic text-gray-500 border-gray-200">
                                                @price($product->getPrice($item['option_ids'])) each
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <label for="quantity-{{ $hash }}"
                                            class="sr-only">{{ __('Quantity') }}</label>
                                        <select id="quantity-{{ $hash }}" name="quantity-{{ $hash }}"
                                            wire:change="changeQuantity('{{ $hash }}', $event.target.value)"
                                            class="max-w-full rounded-md border border-gray-300 py-1.5 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @for ($x = 1; $x <= 10; $x++)
                                                <option value="{{ $x }}" @if ($item['quantity'] == $x) selected="selected" @endif>
                                                    {{ $x }}
                                                </option>
                                            @endfor
                                        </select>

                                        <div class="absolute top-0 right-0">
                                            <button type="button" wire:click="remove('{{ $hash }}')"
                                                class="inline-flex p-2 -m-2 text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">{{ __('Remove') }}</span>
                                                <!-- Heroicon name: solid/x -->
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="flex mt-4 space-x-2 text-sm text-gray-700">
                                    <!-- Heroicon name: solid/check -->
                                    <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('In stock') }}</span>
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div>
                    {{ __('Your cart is empty') }}
                </div>
            @endif
        </section>

        <!-- Order summary -->
        <section aria-labelledby="summary-heading"
            class="px-4 py-6 mt-16 rounded-lg bg-gray-50 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5">
            <h2 id="summary-heading" class="text-lg font-medium text-gray-900">
                {{ __('Order Summary') }}
            </h2>

            <form>
                <label for="discount-code" class="block mt-4 text-sm font-medium text-gray-700">
                    {{ __('Discount code') }}
                </label>
                <div class="flex mt-1 space-x-4">
                    <input type="text" id="discount-code" name="discount-code"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="submit"
                        class="px-4 text-sm font-medium text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">Apply</button>
                </div>
            </form>

            <dl class="mt-6 space-y-4">
                @if (App\Helpers\Taxes::areEnabled())
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">
                            {{ __('Subtotal') }}
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">
                            @price($totalPrice - $totalTaxes)
                        </dd>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <dt class="flex text-sm text-gray-600">
                            <span>
                                {{ __('Taxes') }} ({{ App\Helpers\Taxes::getTaxRatio() * 100 }} %)
                            </span>
                            <a href="#" class="flex-shrink-0 ml-2 text-gray-400 hover:text-gray-500">
                                <span class="sr-only">
                                    {{ __('Learn more about how tax is calculated') }}
                                </span>
                                <!-- Heroicon name: solid/question-mark-circle -->
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">
                            @price($totalTaxes)
                        </dd>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <dt class="text-base font-medium text-gray-900">
                        {{ __('Order total') }}
                    </dt>
                    <dd class="text-base font-medium text-gray-900">
                        @price($totalPrice)
                    </dd>
                </div>
            </dl>

            <div class="mt-6">
                <x-button.link-primary class="w-full px-4 py-3 text-base font-medium text-center "
                    href="{{ route('checkout') }}" loading="true">
                    {{ __('Checkout') }}
                </x-button.link-primary>
            </div>
        </section>
    </form>
</main>
