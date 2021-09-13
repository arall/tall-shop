<main class="mx-auto max-w-7xl sm:pt-16 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto lg:max-w-none">
        <!-- Product -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col-reverse" x-data="{ current: {{ $product->cover ? $product->cover->id : 0 }} }">
                <!-- Image selector -->
                <div class="hidden w-full max-w-2xl mx-auto mt-6 sm:block lg:max-w-none">
                    <div class="grid grid-cols-4 gap-6" aria-orientation="horizontal" role="tablist">
                        @foreach ($product->images as $image)
                            <img @click="current = {{ $image->id }}" alt=""
                                class="relative flex items-center justify-center h-24 text-sm font-medium text-gray-900 uppercase bg-white rounded-md cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-opacity-50"
                                src="{{ $image->getUrl('thumb') }}">
                        @endforeach
                    </div>
                </div>

                <div class="w-full aspect-w-1 aspect-h-1">
                    <!-- Tab panel, show/hide based on tab state. -->
                    @foreach ($product->images as $image)
                        <div id="tabs-2-panel-1" aria-labelledby="tabs-2-tab-1" role="tabpanel" tabindex="0">
                            <img x-show="current == {{ $image->id }}" alt=""
                                @click="$dispatch('img-modal', {  imgModalSrc: '{{ $image->getUrl('large') }}' })"
                                class="object-cover object-center w-full h-full sm:rounded-lg"
                                src="{{ $image->getUrl('mid') }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product info -->
            <div class="px-4 mt-10 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                    {{ $product->name }}
                </h1>

                @if ($product->categories)
                    <h3 class="mb-5 text-sm tracking-widest text-gray-500 title-font">
                        {{ $product->categories->implode('name', ', ') }}
                    </h3>
                @endif

                <div class="mt-3">
                    <h2 class="sr-only">
                        {{ __('Product information') }}
                    </h2>
                    <p class="text-3xl text-gray-900">
                        <x-product.price :price="$price" />
                    </p>
                    <span class="text-gray-600 text-small">
                        @if (App\Helpers\Taxes::areEnabled())
                            @if (App\Helpers\Taxes::productPricesContainTaxes())
                                {{ __('Including taxes') }}
                            @else
                                {{ __('Excluding taxes') }}
                            @endif
                            @if (App\Helpers\Taxes::getTaxRatio())
                                ({{ App\Helpers\Taxes::getTaxRatio() * 100 }}%)
                            @endif
                        @endif
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>

                    <div class="space-y-6 text-base text-gray-700">
                        @markdown($product->description)
                    </div>
                </div>

                <form class="mt-6">
                    @if ($product->options)
                        <div>
                            @foreach ($product->groupedOptions() as $variantId => $options)
                                @php $variant = App\Models\ProductVariant::find($variantId); @endphp
                                <h3 class="text-sm text-gray-600">
                                    {{ $variant->name }}
                                </h3>
                                <x-input.select wire:change="changeOptions" class="mt-3"
                                    wire:model="optionIds.{{ $variant->id }}">
                                    @foreach ($options as $option)
                                        <option value="{{ $option->id }}">
                                            {{ $option->name }}
                                        </option>
                                    @endforeach
                                </x-input.select>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex mt-10 sm:flex-col1">
                        <x-button.primary wire:click="addToCart"
                            class="flex items-center justify-center flex-1 max-w-xs px-8 py-3 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 sm:w-full">
                            <svg wire:loading wire:target="addToCart" class="inline-block w-4 h-4 mr-3 animate-spin"
                                ill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <x-icon.cart wire:loading.remove wire:target="addToCart" class="w-4 h-4 mr-2" />
                            <span>
                                {{ __('Add to cart') }}
                            </span>
                            </x-button>
                    </div>
                </form>

                <section aria-labelledby="details-heading" class="mt-12">
                    <h2 id="details-heading" class="sr-only">
                        {{ __('Additional details') }}
                    </h2>

                    <div class="border-t divide-y divide-gray-200" x-data="{ open: true }">
                        <div>
                            <h3>
                                <!-- Expand/collapse question button -->
                                <button type="button" x-on:click="open = ! open"
                                    class="relative flex items-center justify-between w-full py-6 text-left group"
                                    aria-controls="disclosure-1" aria-expanded="false">
                                    <!-- Open: "text-indigo-600", Closed: "text-gray-900" -->
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ __('More details') }}
                                    </span>
                                    <span class="flex items-center ml-6">
                                        <svg class="block w-6 h-6 text-gray-400 group-hover:text-gray-500" x-show="open"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <svg class="block w-6 h-6 text-indigo-400 group-hover:text-indigo-500"
                                            x-show="! open" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 12H6" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div x-show="open" class="pb-6 prose-sm prose" id="disclosure-1">
                                {!! $product->long_description !!}
                            </div>
                        </div>

                        <!-- More sections... -->
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
