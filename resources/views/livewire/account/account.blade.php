<div class="space-y-6">
    <section>
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 sm:p-6">
                <div>
                    <h2 id="payment_details_heading" class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Profile information') }}
                    </h2>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-6">
                    <div class="col-span-4 sm:col-span-2">
                        <x-label for="firstname">
                            {{ __('First Name') }}
                        </x-label>
                        <x-input.text id="firstname" wire:model="name" />
                        <x-error for="name" />
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                <x-action-message class="mr-3" on="saved.profile">
                    {{ __('Saved.') }}
                </x-action-message>
                <x-button.primary wire:click="saveProfile" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-button.primary>
            </div>
        </div>
    </section>

    <section>
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 sm:p-6">
                <div>
                    <h2 id="payment_details_heading" class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Update Login information') }}
                    </h2>
                    <p class="max-w-2xl text-sm text-gray-500">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-6">
                    <div class="col-span-4 sm:col-span-2">
                        <x-label for="current_password">
                            {{ __('Current Password') }}
                        </x-label>
                        <x-input.text id="current_password" wire:model="current_password" />
                        <x-error for="current_password" />
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-6">
                    <div class="col-span-4 sm:col-span-2">
                        <x-label for="email">
                            {{ __('Email') }}
                        </x-label>
                        <x-input.text id="email" wire:model="email" />
                        <x-error for="email" />
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-6">
                    <div class="col-span-4 sm:col-span-2">
                        <x-label for="new_password">
                            {{ __('New Password') }}
                        </x-label>
                        <x-input.password id="new_password" wire:model.lazy="new_password" />
                        <x-error for="new_password" />
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-6">
                    <div class="col-span-4 sm:col-span-2">
                        <x-label for="new_password_confirmation">
                            {{ __('Confirm Password') }}
                        </x-label>
                        <x-input.password id="new_password_confirmation" wire:model.lazy="new_password_confirmation" />
                        <x-error for="new_password_confirmation" />
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                <x-action-message class="mr-3" on="saved.login">
                    {{ __('Saved.') }}
                </x-action-message>
                <x-button.primary wire:click="saveLogin" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-button.primary>
            </div>
        </div>
    </section>
</div>
