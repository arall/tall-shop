@section('title', __('Create a new account'))

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}">
            <x-logo class="w-auto h-16 mx-auto text-indigo-600" />
        </a>

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            {{ __('Create a new account') }}
        </h2>

        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            {{ __('Or') }}
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                {{ __('sign in to your account') }}
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="register" class="space-y-6">

                <x-input.group label="{{ __('Name') }}" for="name" error="true">
                    <x-input.text id="name" wire:model.lazy="name" />
                </x-input.group>

                <x-input.group label="{{ __('Email address') }}" for="email" error="true">
                    <x-input.email id="email" wire:model.lazy="email" />
                </x-input.group>

                <x-input.group label="{{ __('Password') }}" for="password" error="true">
                    <x-input.password id="password" wire:model.lazy="password" />
                </x-input.group>

                <x-input.group label="{{ __('Confirm Password') }}" for="password_confirmation" error="true">
                    <x-input.password id="password_confirmation" wire:model.lazy="password_confirmation" />
                </x-input.group>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <x-button.primary type="submit" class="flex justify-center w-full px-4 py-2" target="register">
                            {{ __('Register') }}
                        </x-button.primary>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
