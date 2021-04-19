<x-input.group label="{{ __('VAT') }}" for="invoiceAddress.vat" class="sm:col-span-3" error="true">
    <x-input.text id="invoiceAddress.vat" wire:model.defer="invoiceAddress.vat" />
</x-input.group>

<x-input.group label="{{ __('Name') }}" for="invoiceAddress.name" class="sm:col-span-3" error="true">
    <x-input.text id="invoiceAddress.name" wire:model.defer="invoiceAddress.name" />
</x-input.group>

<x-input.group label="{{ __('Phone') }}" for="invoiceAddress.phone" class="sm:col-span-3" error="true">
    <x-input.text id="invoiceAddress.phone" wire:model.defer="invoiceAddress.phone" />
</x-input.group>

<x-input.group label="{{ __('Country') }}" for="invoiceAddress.country" class="sm:col-span-3" error="true">
    <x-input.select id="invoiceAddress.country" wire:model.defer="invoiceAddress.country">
        @foreach ($countries as $id => $name)
            <option value="{{ $name }}">
                {{ $name }}
            </option>
        @endforeach
    </x-input.select>
</x-input.group>

<x-input.group label="{{ __('Address') }}" for="invoiceAddress.address" class="sm:col-span-6" error="true">
    <x-input.text id="invoiceAddress.address" wire:model.defer="invoiceAddress.address" />
</x-input.group>

<x-input.group label="{{ __('City') }}" for="invoiceAddress.city" class="sm:col-span-2" error="true">
    <x-input.text id="invoiceAddress.city" wire:model.defer="invoiceAddress.city" />
</x-input.group>

<x-input.group label="{{ __('State / Province') }}" for="invoiceAddress.region" class="sm:col-span-2" error="true">
    <x-input.text id="invoiceAddress.region" wire:model.defer="invoiceAddress.region" />
</x-input.group>

<x-input.group label="{{ __('ZIP / Postal code') }}" for="invoiceAddress.zip" class="sm:col-span-2" error="true">
    <x-input.text id="invoiceAddress.zip" wire:model.defer="invoiceAddress.zip" />
</x-input.group>
