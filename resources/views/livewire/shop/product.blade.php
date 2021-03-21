<section class="text-gray-600 body-font overflow-hidden">
    <div class="container px-5 py-24 mx-auto">
        <div class="lg:w-4/5 mx-auto flex flex-wrap">
            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                <div class="lg:h-auto h-64 object-cover object-center ">
                    <img id="product-image" class="rounded lg:h-auto h-64" src="{{ $product->getCoverUrl('mid') }}">
                </div>
                <div class="mt-5">
                    @foreach($product->images as $image)
                        <img class="preview float-left mr-5 h-10 " src="{{ $image->getUrl('thumb') }}">
                    @endforeach
                </div>
            </div>
            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                <h2 class="text-sm title-font text-gray-500 tracking-widest">
                    {{ $product->category ? $product->category->name : '' }}
                </h2>
                <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">
                    {{ $product->name }}
                </h1>
                <p class="leading-relaxed">
                    @markdown($product->description)
                </p>
                @if($product->options)
                    <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-100 mb-5">
                        @foreach($product->groupedOptions() as $variantId => $options)
                            @php $variant = App\Models\ProductVariant::find($variantId); @endphp
                            <div class="flex items-center">
                                <span class="mr-3">
                                    {{ $variant->name }}
                                </span>
                                <div class="relative">
                                    <x-input.select wire:change="changeOptions" wire:model="optionIds.{{ $variant->id }}">
                                        @foreach($options as $option)
                                            <option value="{{ $option->id }}">
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </x-input.select>
                                    <span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none flex items-center justify-center">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="title-font font-medium text-2xl text-gray-900">
                        @price($price)
                    </span>
                    <x-button.primary wire:click="addToCart" class="inline-flex items-center">
                        <x-icon.cart class="w-4 h-4 mr-2" />
                        <span>
                            {{ __('Add to cart') }}
                        </span>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>
