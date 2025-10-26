@php use Illuminate\Contracts\Auth\MustVerifyEmail; @endphp
<section>
    <header class="flex items-center mb-4 pb-2 border-b border-blue-800">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <x-input-label for="code" :value="__('User Code')"/>
                <x-text-input id="code" type="text" class="mt-1 block w-full"
                              :value="$code"
                              disabled/>
            </div>

            <div class="flex items-center justify-start pt-6">
                <label for="is_active" class="flex items-center">
                    <x-checkbox wire:model="is_active" id="is_active" name="is_active" disabled/>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active User') }}</span>
                </label>
            </div>

            <div>
                <x-input-label for="name" :value="__('Name')"/>
                <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                              autofocus autocomplete="name"/>
                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')"/>
                <x-text-input wire:model="last_name" id="last_name" name="last_name" type="text"
                              class="mt-1 block w-full" required
                              autocomplete="last_name"/>
                <x-input-error class="mt-2" :messages="$errors->get('last_name')"/>
            </div>

            <div>
                <x-input-label for="document_type" :value="__('Document Type')"/>
                <select wire:model="document_type" id="document_type" name="document_type"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="CI">CI</option>
                    <option value="PASSPORT">Pasaporte</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('document_type')"/>
            </div>

            <div>
                <x-input-label for="document_number" :value="__('Document Number')"/>
                <x-text-input wire:model="document_number" id="document_number" name="document_number" type="number"
                              class="mt-1 block w-full" required/>
                <x-input-error class="mt-2" :messages="$errors->get('document_number')"/>
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')"/>
                <x-text-input wire:model="phone" id="phone" name="phone" type="tel"
                              class="mt-1 block w-full"/>
                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required
                              autocomplete="username"/>
                <x-input-error class="mt-2" :messages="$errors->get('email')"/>

                @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                @endif
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Address')"/>
                <x-text-input wire:model="address" id="address" name="address" type="text"
                              class="mt-1 block w-full"/>
                <x-input-error class="mt-2" :messages="$errors->get('address')"/>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
