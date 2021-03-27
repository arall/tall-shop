@section('title', __('Confirm your password'))

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}">
            <x-logo class="w-auto h-16 mx-auto text-indigo-600" />
        </a>

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            {{ __('Confirm your password') }}
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            {{ __('Please confirm your password before continuing') }}
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="confirm">
                <x-input.group label="{{ __('Password') }}" for="password" error="true">
                    <x-input.password id="password" wire:model.lazy="password" />
                </x-input.group>

                <div class="flex items-center justify-end mt-6">
                    <div class="text-sm leading-5">
                        <x-button.link-primary href="{{ route('password.request') }}">
                            {{ __('Forgot your password?' ) }}
                        </x-button.link-primary>
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <x-button.primary type="submit" class="flex justify-center w-full px-4 py-2" target="confirm">
                            {{ __('Confirm password') }}
                        </x-button.primary>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
