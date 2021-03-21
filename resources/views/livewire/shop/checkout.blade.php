<div class="container mx-auto px-6">
    <div class="flex shadow-md my-10 bg-white">
        <div class="w-3/4 px-10 py-10">

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Shipping Information') }}
                </h3>
                <form wire:submit.prevent="saveAddress">
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="firstname" class="block text-sm font-medium text-gray-700">
                                {{ __('First name') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="firstname" wire:model="user.name" />
                                @error('user.name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">
                                {{ __('Last name') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="lastname" wire:model="profile.lastname" />
                                @error('profile.lastname') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                {{ __('Phone') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="phone" wire:model="profile.phone" />
                                @error('profile.phone') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="country" class="block text-sm font-medium text-gray-700">
                                {{ __('Country') }}
                            </label>
                            <div class="mt-1">
                                <x-input.select id="country" wire:model="profile.country">
                                    @foreach ($countries as $id => $name)
                                        <option value="{{ $name }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </x-input.select>
                                @error('profile.country') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="street_address" class="block text-sm font-medium text-gray-700">
                                {{ __('Address') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="address" wire:model="profile.address" />
                                @error('profile.address') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="city" class="block text-sm font-medium text-gray-700">
                                {{ __('City') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="city" wire:model="profile.city" />
                                @error('profile.city') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="state" class="block text-sm font-medium text-gray-700">
                                {{ __('State / Province') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="state" wire:model="profile.region" />
                                @error('profile.region') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="zip" class="block text-sm font-medium text-gray-700">
                                {{ __('ZIP / Postal code') }}
                            </label>
                            <div class="mt-1">
                                <x-input.text id="zip" wire:model="profile.zip" />
                                @error('profile.zip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-button.primary type="submit">
                                {{ __('Save') }}
                            </x-button.primary>
                        </div>
                    </div>
                </form>
            </div>

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Shipping method') }}
                </h3>
                <form wire:submit.prevent="saveShipment">
                    <div class="mt-6">
                        <ul class="relative bg-white rounded-md -space-y-px" x-ref="radiogroup">
                            @foreach ($shippingCarriers as $shippingCarrier)
                                <li>
                                    <div :class="{ 'border-gray-200': !(active === 0), 'bg-indigo-50 border-indigo-200 z-10': active === 0 }"
                                        class="relative border @if($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                        p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-200">
                                        <label class="flex items-center text-sm cursor-pointer" wire:click="calculateTotalPrice" >
                                            <input wire:model="shippingCarrierId" name="shipping" type="radio" value="{{ $shippingCarrier->id }}"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 cursor-pointer border-gray-300"
                                                aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                                            <span class="ml-3 font-medium text-gray-900">
                                                {{ $shippingCarrier->name }}
                                            </span>
                                        </label>
                                        <p id="plan-option-pricing-0"
                                            class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                                            <span
                                                :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }"
                                                class="font-medium text-gray-900">
                                                @price($shippingCarrier->total_price)
                                            </span>
                                        </p>
                                        <p id="plan-option-limit-0"
                                            :class="{ 'text-indigo-700': active === 0, 'text-gray-500': !(active === 0) }"
                                            class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right text-gray-500">
                                            {{ $shippingCarrier->eta }} {{ __('Hours') }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <x-button.primary type="submit">
                                    {{ __('Save') }}
                                </x-button.primary>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="w-1/4 px-8 py-10">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __('Order Summary') }}
            </h3>

            <div class="border-t mt-5 flex font-semibold justify-between py-6 text-sm uppercase">
                <span>
                    {{ __('Total Cost') }}
                </span>
                <span>
                    @price($price)
                </span>
            </div>
        </div>
    </div>
</div>
