<section class="overflow-hidden text-gray-600 body-font">
    <div class="container px-5 py-24 mx-auto">
        <div class="flex flex-wrap mx-auto lg:w-4/5">

            <!-- Image gallery -->
            <div class="w-full mt-6 lg:w-1/2 lg:pl-10 lg:py-6 lg:mt-0"
                x-data="{ current: {{ $product->cover ? $product->cover->id : 0 }} }">
                <div class="object-cover object-center h-64 lg:h-auto">
                    @foreach ($product->images as $image)
                        <img
                            x-show="current == {{ $image->id }}"
                            @click="$dispatch('img-modal', {  imgModalSrc: '{{ $image->getUrl('large') }}' })"
                            class="h-64 rounded cursor-pointer lg:h-auto"
                            src="{{ $image->getUrl('mid') }}"
                        >
                    @endforeach
                </div>
                <div class="mt-5">
                    @foreach ($product->images as $image)
                        <img
                            @click="current = {{ $image->id }}"
                            class="float-left h-10 mr-5 cursor-pointer"
                            src="{{ $image->getUrl('thumb') }}"
                        >
                    @endforeach
                </div>
            </div>

            <!-- Image modal -->
            <div x-data="{ imgModal : false, imgModalSrc : '' }">
                <template @img-modal.window="imgModal = true; imgModalSrc = $event.detail.imgModalSrc;" x-if="imgModal">
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="imgModalSrc = ''"
                        class="fixed inset-0 z-50 flex items-center justify-center w-full p-2 overflow-hidden bg-black bg-opacity-75 h-100">
                        <div @click.away="imgModal = ''" class="flex flex-col max-w-3xl max-h-full overflow-auto">
                            <div class="z-50">
                                <button @click="imgModal = ''"
                                    class="float-right pt-2 pr-2 outline-none focus:outline-none">
                                    <svg class="text-white fill-current " xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 18 18">
                                        <path
                                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-2">
                                <img :alt="imgModalSrc" class="object-contain h-1/2-screen" :src="imgModalSrc">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Product details -->
            <div class="w-full mt-6 lg:w-1/2 lg:pl-10 lg:py-6 lg:mt-0">
                <h1 class="mb-1 text-3xl font-medium text-gray-900 title-font">
                    {{ $product->name }}
                </h1>
                @if($product->categories)
                    <h3 class="mb-5 text-sm tracking-widest text-gray-500 title-font">
                        {{  $product->categories->implode('name', ', ') }}
                    </h3>
                @endif
                <p class="leading-relaxed">
                    @markdown($product->description)
                </p>
                @if ($product->options)
                    <div class="flex items-center pb-5 mt-6 mb-5 border-b-2 border-gray-100">
                        @foreach ($product->groupedOptions() as $variantId => $options)
                            @php $variant = App\Models\ProductVariant::find($variantId); @endphp
                            <div class="flex items-center">
                                <span class="mr-3">
                                    {{ $variant->name }}
                                </span>
                                <div class="relative">
                                    <x-input.select wire:change="changeOptions"
                                        wire:model="optionIds.{{ $variant->id }}">
                                        @foreach ($options as $option)
                                            <option value="{{ $option->id }}">
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </x-input.select>
                                    <span
                                        class="absolute top-0 right-0 flex items-center justify-center w-10 h-full text-center text-gray-600 pointer-events-none">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-4 h-4"
                                            viewBox="0 0 24 24">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-2xl font-medium text-gray-900 title-font">
                        @price($price)
                    </span>
                    <x-button.primary wire:click="addToCart" class="inline-flex items-center">
                        <svg wire:loading wire:target="addToCart" class="inline-block w-4 h-4 mr-3 animate-spin" ill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <x-icon.cart wire:loading.remove wire:target="addToCart" class="w-4 h-4 mr-2" />
                        <span>
                            {{ __('Add to cart') }}
                        </span>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>
