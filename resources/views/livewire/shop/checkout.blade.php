<main class="max-w-2xl px-4 mx-auto sm:px-6 lg:max-w-7xl lg:px-8">
    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
        {{ __('Checkout') }}
    </h1>
    <form class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
        <section aria-labelledby="cart-heading" class="space-y-6 lg:col-span-7">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ __('Shipping Information') }}
            </h3>
            <div class="grid grid-cols-1 mt-6 gap-y-6 gap-x-4 sm:grid-cols-6">

                @if (count($addresses))
                    <x-input.group label="{{ __('Saved Addresses') }}" for="addressId" class="sm:col-span-6">
                        <x-input.select id="addressId" wire:model="addressId">
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}">
                                    {{ $address->getText() }}
                                </option>
                            @endforeach
                            <option value="0">
                                {{ __('Use a new address') }}
                            </option>
                        </x-input.select>
                    </x-input.group>
                @endif

                <x-account.forms.address :countries="$countries" />
            </div>

            <!-- Invoicing info -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Invoicing Information') }}
                </h3>
                <div class="grid grid-cols-1 mt-6 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <x-input.group label="{{ __('Saved Addresses') }}" for="invoiceAddressId" class="sm:col-span-6">
                        <x-input.select id="invoiceAddressId" wire:model="invoiceAddressId">
                            <option value="0">
                                {{ __('Use the same as the shipping address') }}
                            </option>
                            <option value="-1">
                                {{ __('Use a new address') }}
                            </option>
                            @foreach ($invoiceAddresses as $address)
                                <option value="{{ $address->id }}">
                                    {{ $address->getText() }}
                                </option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                </div>
                @if ($showInvoiceForm)
                    <div class="grid grid-cols-1 mt-6 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <x-account.forms.invoice-address :countries="$countries" />
                    </div>
                @endif
            </div>

            <!-- Shipping method -->
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Shipping method') }}
                </h3>
                <div class="mt-6">
                    <ul class="relative -space-y-px rounded-md" x-ref="radiogroup"
                        x-data="{ active: {{ $shippingCarrierId ?: 0 }} }">
                        @foreach ($shippingCarriers as $shippingCarrier)
                            <li>
                                <div :class="{ 'border-gray-200': active !== {{ $shippingCarrier->id }}, 'bg-indigo-50 border-indigo-200 z-10': active === {{ $shippingCarrier->id }} }"
                                    class="relative border @if ($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-200">
                                    <label @click="active = {{ $shippingCarrier->id }}"
                                        class="flex items-center text-sm cursor-pointer"
                                        wire:click="calculateTotalPrice">
                                        <input wire:model="shippingCarrierId" name="shippingCarrierId" type="radio"
                                            value="{{ $shippingCarrier->id }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 cursor-pointer focus:ring-indigo-500"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                                        <span class="ml-3 font-medium">
                                            {{ $shippingCarrier->name }}
                                        </span>
                                    </label>
                                    <p id="plan-option-pricing-0"
                                        class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                                        <span
                                            :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }">
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
                                <div :class="{
                                    'border-gray-200': active !== {{ $paymentMethod->id }},
                                    'bg-indigo-50 border-indigo-200 z-10': active === {{ $paymentMethod->id }}
                                }"
                                    class="
                                    @if ($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                    relative border p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-20
                                ">
                                    <label @click="active = {{ $paymentMethod->id }}"
                                        class="flex items-center text-sm cursor-pointer"
                                        wire:click="calculateTotalPrice">
                                        <input wire:model="paymentMethodId" name="paymentMethodId" type="radio"
                                            value="{{ $paymentMethod->id }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 cursor-pointer focus:ring-indigo-500"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                                        <span class="ml-3 font-medium">
                                            {{ $paymentMethod->name }}
                                        </span>
                                    </label>
                                    @if ($paymentMethod->price > 0)
                                        <p id="plan-option-pricing-0"
                                            class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                                            <span
                                                :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }">
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
        </section>
        <section aria-labelledby="summary-heading"
            class="px-4 py-6 mt-16 rounded-lg bg-gray-50 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ __('Order Summary') }}
            </h3>

            <dl class="mt-6 space-y-4">
                @if (App\Helpers\Taxes::areEnabled())
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">
                            {{ __('Subtotal') }}
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">
                            @price($price - $taxes)
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
                            @price($taxes)
                        </dd>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <dt class="text-base font-medium text-gray-900">
                        {{ __('Order total') }}
                    </dt>
                    <dd class="text-base font-medium text-gray-900">
                        @price($price)
                    </dd>
                </div>
            </dl>

            <div class="mt-6">
                <x-button.primary class="w-full px-4 py-3 text-base font-medium text-center" wire:click="save"
                    target="save">
                    {{ __('Place order') }}
                </x-button.primary>
            </div>
        </section>
    </form>
</main>
