<div class="container mx-auto px-6">
    <div class="flex shadow-md my-10 bg-white">
        <div class="w-3/4 px-10 py-10 space-y-6">
            <!-- Shipping info -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Shipping Information') }}
                </h3>
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="firstname" class="block text-sm font-medium text-gray-700">
                            {{ __('First name') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="firstname" wire:model="user.name" />
                            @error('user.name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="lastname" class="block text-sm font-medium text-gray-700">
                            {{ __('Last name') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="lastname" wire:model="profile.lastname" />
                            @error('profile.lastname') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            {{ __('Phone') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="phone" wire:model="profile.phone" />
                            @error('profile.phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
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
                            @error('profile.country') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="street_address" class="block text-sm font-medium text-gray-700">
                            {{ __('Address') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="address" wire:model="profile.address" />
                            @error('profile.address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="city" class="block text-sm font-medium text-gray-700">
                            {{ __('City') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="city" wire:model="profile.city" />
                            @error('profile.city') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="state" class="block text-sm font-medium text-gray-700">
                            {{ __('State / Province') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="state" wire:model="profile.region" />
                            @error('profile.region') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="zip" class="block text-sm font-medium text-gray-700">
                            {{ __('ZIP / Postal code') }}
                        </label>
                        <div class="mt-1">
                            <x-input.text id="zip" wire:model="profile.zip" />
                            @error('profile.zip') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping method -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Shipping method') }}
                </h3>
                <div class="mt-6">
                    <ul class="relative rounded-md -space-y-px" x-ref="radiogroup" x-data="{ active: {{ $shippingCarrierId ?: 0 }} }">
                        @foreach ($shippingCarriers as $shippingCarrier)
                            <li>
                                <div :class="{ 'border-gray-200': active !== {{ $shippingCarrier->id }}, 'bg-indigo-50 border-indigo-200 z-10': active === {{ $shippingCarrier->id }} }"
                                    class="relative border @if($loop->first) {{ 'rounded-tl-md rounded-tr-md' }} @elseif($loop->last) {{ 'rounded-bl-md rounded-br-md' }} @endif
                                    p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-3 border-gray-200">
                                    <label @click="active = {{ $shippingCarrier->id }}" class="flex items-center text-sm cursor-pointer" wire:click="calculateTotalPrice" >
                                        <input wire:model="shippingCarrierId" name="shippingCarrierId" type="radio" value="{{ $shippingCarrier->id }}"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 cursor-pointer border-gray-300"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                                        <span class="ml-3 font-medium">
                                            {{ $shippingCarrier->name }}
                                        </span>
                                    </label>
                                    <p id="plan-option-pricing-0"
                                        class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                                        <span :class="{ 'text-indigo-900': active === 0, 'text-gray-900': !(active === 0) }">
                                            @price($shippingCarrier->price)
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
                    @error('shippingCarrierId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Payment method -->
            <div id="paymentMethod" x-data="{ active: {{ $paymentMethodId ?: 0 }}, stripe: false }">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Payment method') }}
                </h3>
                <div class="mt-6">
                    <ul class="relative rounded-md -space-y-px">
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
                                        @click="
                                            active = {{ $paymentMethod->id }};
                                            stripe = {{ $paymentMethod->type == 'stripe' ? 'true' : 'false' }};
                                        "
                                        class="flex items-center text-sm cursor-pointer"
                                        wire:click="calculateTotalPrice"
                                    >
                                        <input
                                            wire:model="paymentMethodId" name="paymentMethodId" type="radio" value="{{ $paymentMethod->id }}"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 cursor-pointer border-gray-300"
                                            aria-describedby="plan-option-pricing-0 plan-option-limit-0"
                                        >
                                        <span class="ml-3 font-medium">
                                            {{ $paymentMethod->name }}
                                        </span>
                                    </label>
                                    @if($paymentMethod->price > 0)
                                        <p id="plan-option-pricing-0" class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
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

                <!-- Stripe form -->
                @if($hasStripe)
                    <div wire:ignore>
                        <!-- Stripe Credit Card form -->
                        <script src="https://js.stripe.com/v3/"></script>
                        <script>
                            var stripe = Stripe('{{ getenv('OMNIPAY_STRIPE_PUBLIC') }}');
                            var elements = stripe.elements();
                        </script>

                        <div x-show="stripe">
                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="carholder" class="block text-sm font-medium text-gray-700">
                                        {{ __('Card Holder Name') }}
                                    </label>
                                    <div class="mt-1">
                                        <x-input.text id="carholder"/>
                                    </div>
                                </div>
                                <div class="sm:col-span-6">
                                    <label for="card-element" class="block text-sm font-medium text-gray-700">
                                        {{ __('Credit Card details') }}
                                    </label>
                                </div>
                            </div>
                            <div id="card-element"></div>
                            <div id="card-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
                        </div>

                        <script>
                            var card = elements.create('card');
                            card.mount('#card-element');

                            function saveStripe(){
                                stripe.createPaymentMethod({
                                    type: 'card',
                                    card: card,
                                    billing_details: {
                                        name: document.getElementById('carholder'),
                                    },
                                }).then(function(result) {
                                    var errorElement = document.getElementById('card-errors');
                                    errorElement.textContent = '';
                                    if (result.error) {
                                        errorElement.textContent = result.error.message;
                                    } else {
                                        @this.set('stripePaymentMethod', result.paymentMethod);
                                        @this.save();
                                    }
                                });
                            }
                        </script>
                    </div>
                @endif
            </div>

            <!-- Continue -->
            <div>
                <div class="flex justify-end">
                    <x-button.primary onClick="save()" class="inline-flex items-center">
                        <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-3 inline-block" ill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>
                            {{ __('Place order') }}
                        </span>
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Summary -->
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

<script>
    function save(){
        if(document.getElementById('paymentMethod').__x.$data.stripe){
            return saveStripe();
        }

        return @this.save();
    }
</script>
