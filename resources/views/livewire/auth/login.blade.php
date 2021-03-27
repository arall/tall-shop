@section('title', __('Sign in to your account'))

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}">
            <x-logo class="w-auto h-16 mx-auto text-indigo-600" />
        </a>

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            {{ __('Sign in to your account') }}
        </h2>
        @if (Route::has('register'))
            <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
                {{ __('Or') }}
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    {{ __('create a new account') }}
                </a>
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="authenticate" class="space-y-6">
                <x-input.group label="{{ __('Email address') }}" for="email" error="true">
                    <x-input.email id="email" wire:model.lazy="email" />
                </x-input.group>

                <x-input.group label="{{ __('Password') }}" for="password" error="true">
                    <x-input.password id="password" wire:model.lazy="password" />
                </x-input.group>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model.lazy="remember" id="remember" type="checkbox" class="form-checkbox w-4 h-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            {{ __('Remember') }}
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                </div>

                <div>
                    <span class="block w-full rounded-md shadow-sm">
                        <x-button.primary type="submit" class="flex justify-center w-full px-4 py-2" target="authenticate">
                            {{ __('Sign in') }}
                        </x-button.primary>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
