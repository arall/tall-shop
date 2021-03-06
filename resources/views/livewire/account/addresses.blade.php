<div class="space-y-6">
    <section>
        <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
            <div class="inline-block w-full px-4 sm:px-6">
                <div class="float-left">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Addresses') }}
                    </h2>
                </div>
                <div class="float-right">
                    <x-button.primary wire:click="create" target="create">
                        {{ __('Add') }}
                    </x-button.primary>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-t border-gray-200">
                            @if (count($addresses))
                                <ul class="relative border-b border-gray-200 divide-y divide-gray-200">
                                    @foreach ($addresses as $address)
                                        <li
                                            class="relative py-5 pl-4 pr-6 hover:bg-gray-50 sm:py-6 sm:pl-6 lg:pl-8 xl:pl-6">
                                            <div class="flex items-center justify-between space-x-4">
                                                <div class="min-w-0 space-y-3">
                                                    <div class="relative flex items-center group">
                                                        <button
                                                            x-data="{ favorite: {{ $address->favorite }}}"
                                                            wire:click="favorite({{ $address->id }})"
                                                            class="relative mr-2 bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                            type="button">
                                                            <svg
                                                                :class="{ 'text-yellow-300': favorite == 1, 'text-gray-300': favorite == 0}"
                                                                class="w-5 h-5 hover:text-yellow-400"
                                                                x-state:on="Starred" x-state:off="Not Starred"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <span
                                                            class="text-sm font-medium text-gray-500 truncate group-hover:text-gray-900">
                                                            {{ $address->getText() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-col items-end flex-shrink-0 space-y-3 sm:flex">
                                                    <div x-data="{ open: false }" @keydown.escape.stop="open = false"
                                                        @click.away="open = false"
                                                        class="relative flex items-center justify-end">
                                                        <button type="button"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                                            @click="open = !open"
                                                            aria-haspopup="true" x-bind:aria-expanded="open">
                                                            <svg class="w-5 h-5"
                                                                x-description="Heroicon name: solid/dots-vertical"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path
                                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                                </path>
                                                            </svg>
                                                        </button>

                                                        <div x-description="Dropdown menu, show/hide based on menu state."
                                                            x-show="open"
                                                            x-transition:enter="transition ease-out duration-100"
                                                            x-transition:enter-start="transform opacity-0 scale-95"
                                                            x-transition:enter-end="transform opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-75"
                                                            x-transition:leave-start="transform opacity-100 scale-100"
                                                            x-transition:leave-end="transform opacity-0 scale-95"
                                                            class="absolute z-10 w-48 mx-3 mt-1 origin-top-right bg-white divide-y divide-gray-200 rounded-md shadow-lg right-7 ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                            role="menu" aria-orientation="vertical"
                                                            style="display: none;">
                                                            <button wire:click="edit({{ $address->id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900"
                                                                role="menuitem">
                                                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
                                                                    x-description="Heroicon name: solid/pencil-alt"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                                    </path>
                                                                    <path fill-rule="evenodd"
                                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                                {{ __('Edit') }}
                                                            </button>
                                                            <button wire:click="destroy({{ $address->id }})" @click="open = false"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900"
                                                                role="menuitem">
                                                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
                                                                    x-description="Heroicon name: solid/trash"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path fill-rule="evenodd"
                                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            @else
                                <div class="m-10 text-center text-gray-800">
                                    {{ __('You didn\'t add any address yet.') }}
                                </div>
                            @endif

                            <x-modal.dialog wire:model.defer="showModal">
                                <x-slot name="title">

                                </x-slot>
                                <x-slot name="content">
                                    <div class="grid grid-cols-1 mt-6 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <input type="hidden" wire:model="address.id">

                                        <x-account.forms.address :countries="$countries"/>
                                    </div>
                                </x-slot>
                                <x-slot name="footer">
                                    <x-button.secondary wire:click="$set('showModal', false)">
                                        {{ __('Cancel') }}
                                    </x-button.secondary>
                                    <x-button.primary class="float-right" wire:click="save" target="save">
                                        {{ __('Save') }}
                                    </x-button.primary>
                                </x-slot>
                            </x-modal.dialog>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
