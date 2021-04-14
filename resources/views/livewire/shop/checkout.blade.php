<div class="container px-6 mx-auto">
    <div class="my-10 bg-white shadow-md lg:flex">
        <div class="px-10 py-10 space-y-6 lg:w-3/4">
            <!-- Shipping info -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Shipping Information') }}
                </h3>
                <div class="grid grid-cols-1 mt-6 gap-y-6 gap-x-4 sm:grid-cols-6">

                    @if(count($addresses))
                        <x-input.group label="{{ __('Saved Addresses') }}" for="addressId" class="sm:col-span-6">
                            <x-input.select id="addressId" wire:model="addressId">
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->getText() }}
                                    </option>
                                @endforeach
                                <option value="-1">
                                    {{ __('Use a new address') }}
                                </option>
                            </x-input.select>
                        </x-input.group>
                    @endif

                    <x-account.forms.address :countries="$countries"/>
                </div>
            </div>

            <!-- Shipping method -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Shipping method') }}
                </h3>
                <div class="mt-6">
                    <ul class="relative -space-y-px rounded-md" x-ref="radiogroup" x-data="{ active: {{ $shippingCarrierId ?: 0 }} }">
                        @foreach ($shippingCarriers as $shippingCarrier)
                            <li>
                                <div :class="{ 'border-gray-200': active !== {{ $shippingCarrier->id }}, 'bg-indigo-50 border-indigo-200 z-10': active === {{ $shippingCarrier->id }} }"
                                    class="relative border @if($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                    p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-200">
                                    <label @click="active = {{ $shippingCarrier->id }}" class="flex items-center text-sm cursor-pointer" wire:click="calculateTotalPrice" >
                                        <input wire:model="shippingCarrierId" name="shippingCarrierId" type="radio" value="{{ $shippingCarrier->id }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 cursor-pointer focus:ring-indigo-500"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                                        <span class="ml-3 font-medium">
                                            {{ $shippingCarrier->name }}
                                        </span>
                                    </label>
                                    <p id="plan-option-pricing-0"
                                        class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                                        <span :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }">
                                            @price($shippingCarrier->price)
                                        </span>
                                    </p>
                                    <p id="plan-option-limit-0"
                                        :class="{ 'text-indigo-700': active === 0, 'text-gray-500': !(active === 0) }"
                                        class="pl-1 ml-6 text-sm text-gray-500 md:ml-0 md:pl-0 md:text-right">
                                        {{ $shippingCarrier->eta }} {{ __('Hours') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @error('shippingCarrierId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Payment method -->
            <div id="paymentMethod">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Payment method') }}
                </h3>
                <div class="mt-6">
                    <ul class="relative -space-y-px rounded-md" x-data="{ active: {{ $paymentMethodId ?: 0 }}}">
                        @foreach ($paymentMethods as $paymentMethod)
                            <li>
                                <div
                                    :class="{
                                        'border-gray-200': active !== {{ $paymentMethod->id }},
                                        'bg-indigo-50 border-indigo-200 z-10': active === {{ $paymentMethod->id }}
                                    }"
                                    class="
                                        @if($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                        relative border p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-20
                                    "
                                >
                                    <label
                                        @click="active = {{ $paymentMethod->id }}"
                                        class="flex items-center text-sm cursor-pointer"
                                        wire:click="calculateTotalPrice"
                                    >
                                        <input
                                            wire:model="paymentMethodId" name="paymentMethodId" type="radio" value="{{ $paymentMethod->id }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 cursor-pointer focus:ring-indigo-500"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0"
                                        >
                                        <span class="ml-3 font-medium">
                                            {{ $paymentMethod->name }}
                                        </span>
                                    </label>
                                    @if($paymentMethod->price > 0)
                                        <p id="plan-option-pricing-0" class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                                            <span
                                                :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }"
                                            >
                                                @price($paymentMethod->price)
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @error('paymentMethodId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="px-8 py-10 lg:w-1/4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ __('Order Summary') }}
            </h3>

            <div class="py-6 mt-5 space-y-5 text-sm uppercase border-t">
                @if(App\Helpers\Taxes::areEnabled())
                    <div class="flex justify-between">
                        <span>
                            {{ __('Taxes') }}
                        </span>
                        <span>
                            @price($taxes)
                        </span>
                    </div>
                @endif
                <div class="flex justify-between font-semibold">
                    <span>
                        {{ __('Total Cost') }}
                    </span>
                    <span>
                        @price($price)
                    </span>
                </div>
            </div>

            <!-- Continue -->
            <div>
                <div class="flex justify-end">
                    <x-button.primary wire:click="save" target="save">
                        {{ __('Place order') }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
