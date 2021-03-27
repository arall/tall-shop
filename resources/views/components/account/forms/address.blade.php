<x-input.group label="{{ __('First Name') }}" for="address.firstname" class="sm:col-span-3" error="true">
    <x-input.text id="address.firstname" wire:model.defer="address.firstname" />
</x-input.group>

<x-input.group label="{{ __('Last Name') }}" for="address.lastname" class="sm:col-span-3" error="true">
    <x-input.text id="address.lastname" wire:model.defer="address.lastname" />
</x-input.group>

<x-input.group label="{{ __('Phone') }}" for="address.phone" class="sm:col-span-3" error="true">
    <x-input.text id="address.phone" wire:model.defer="address.phone" />
</x-input.group>

<x-input.group label="{{ __('Country') }}" for="address.country" class="sm:col-span-3" error="true">
    <x-input.select id="address.country" wire:model.defer="address.country">
        @foreach ($countries as $id => $name)
            <option value="{{ $name }}">
                {{ $name }}
            </option>
        @endforeach
    </x-input.select>
</x-input.group>

<x-input.group label="{{ __('Address') }}" for="address.address" class="sm:col-span-6" error="true">
    <x-input.text id="address.address" wire:model.defer="address.address" />
</x-input.group>

<x-input.group label="{{ __('City') }}" for="address.city" class="sm:col-span-2" error="true">
    <x-input.text id="address.city" wire:model.defer="address.city" />
</x-input.group>

<x-input.group label="{{ __('State / Province') }}" for="address.region" class="sm:col-span-2" error="true">
    <x-input.text id="address.region" wire:model.defer="address.region" />
</x-input.group>

<x-input.group label="{{ __('ZIP / Postal code') }}" for="address.zip" class="sm:col-span-2" error="true">
    <x-input.text id="address.zip" wire:model.defer="address.zip" />
</x-input.group>
