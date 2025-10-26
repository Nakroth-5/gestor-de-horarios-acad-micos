<section>
    <header class="flex items-center mb-4 pb-2 border-b border-blue-800">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
    </header>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __("Update your account's profile information and email address.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <x-input-label for="code" :value="__('User Code')"/>
                <x-text-input id="code" type="text" class="mt-1 block w-full"
                              :value="old('code', $user->code ?? 'N/A')"
                              disabled/>
            </div>

            <div class="flex items-center justify-start pt-6">
                <label for="is_active" class="flex items-center">
                    <x-checkbox id="is_active" name="is_active"
                                :checked="old('is_active', $user->is_active ?? false)"
                                disabled/>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active User') }}</span>
                </label>
            </div>

            <div>
                <x-input-label for="name" :value="__('Name')"/>
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name"/>
                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')"/>
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autocomplete="last_name"/>
                <x-input-error class="mt-2" :messages="$errors->get('last_name')"/>
            </div>

            <div>
                <x-input-label for="document_type" :value="__('Document Type')"/>
                <select id="document_type" name="document_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="CI" @selected(old('document_type', $user->document_type) == 'CI')>CI</option>
                    <option value="PASSPORT" @selected(old('document_type', $user->document_type) == 'PASSPORT')>Pasaporte</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('document_type')"/>
            </div>

            <div>
                <x-input-label for="document_number" :value="__('Document Number')"/>
                <x-text-input id="document_number" name="document_number" type="number" class="mt-1 block w-full" :value="old('document_number', $user->document_number)" required/>
                <x-input-error class="mt-2" :messages="$errors->get('document_number')"/>
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')"/>
                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)"/>
                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username"/>
                <x-input-error class="mt-2" :messages="$errors->get('email')"/>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Address')"/>
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)"/>
                <x-input-error class="mt-2" :messages="$errors->get('address')"/>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
