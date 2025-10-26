<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Usuario' : 'Crear Usuario'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="code" :value="__('Code')"/>
            <x-text-input wire:model="form.code" id="code" name="code" type="text" class="mt-1 block w-full" required
                          autofocus disabled/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div class="flex items-center justify-start pt-6">
            <label for="form.is_active" class="flex items-center">
                <x-checkbox wire:model="form.is_active" id="is_active" name="is_active"/>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active User') }}</span>
            </label>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')"/>
            <x-text-input wire:model="form.name" id="name" name="name" type="text" class="mt-1 block w-full" required
                          autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.name')"/>
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last Name')"/>
            <x-text-input wire:model="form.last_name" id="last_name" name="last_name" type="text"
                          class="mt-1 block w-full" required
                          autofocus autocomplete="last_name"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.name')"/>
        </div>

        <div>
            <x-input-label for="document_type" :value="__('Document Type')"/>
            <select wire:model="form.document_type" id="document_type" name="document_type"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="CI">CI</option>
                <option value="PASSPORT">Pasaporte</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.document_type')"/>
        </div>

        <div>
            <x-input-label for="document_number" :value="__('Document Number')"/>
            <x-text-input wire:model="form.document_number" id="document_number" name="document_number" type="number"
                          class="mt-1 block w-full" required/>
            <x-input-error class="mt-2" :messages="$errors->get('form.document_number')"/>
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')"/>
            <x-text-input wire:model="form.phone" id="phone" name="phone" type="tel"
                          class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.phone')"/>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input wire:model="form.email" id="email" name="email" type="email" class="mt-1 block w-full" required
                          autocomplete="username"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.email')"/>

            @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            @endif
        </div>

        <div>
            <x-input-label value="{{ $editing ? 'Nueva Contraseña (Opcional)' : 'Contraseña' }}" />
            <x-text-input wire:model="form.password" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="form.password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-2" />
        </div>
    </div>


    <div class="md:col-span-2">
        <x-input-label for="address" :value="__('Address')"/>
        <x-text-input wire:model="form.address" id="address" name="address" type="text"
                      class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('form.address')"/>
    </div>

    {{--Role(s) --}}
    <div>
        <x-input-label for="roles" :value="__('Roles')"/>
        <x-table.select
            wire:model="form.roles" id="roles"
            multiple
            required
            class="mt-2 block w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-slate-600 dark:text-slate-400 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
            @foreach($relations as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </x-table.select>
        <x-input-error class="mt-2" :messages="$errors->get('form.roles')"/>
    </div>
</x-modal-base>
