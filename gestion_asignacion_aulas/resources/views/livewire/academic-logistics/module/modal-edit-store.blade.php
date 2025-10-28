<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Modulo' : 'Crear Modulo'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="code" :value="__('Code')"/>
            <x-text-input wire:model="form.code" id="code" name="code" type="text" class="mt-1 block w-full" required
                          autofocus/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div class="flex items-center justify-start pt-6">
            <label for="form.is_active" class="flex items-center">
                <x-checkbox wire:model="form.is_active" id="is_active" name="is_active"/>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active Module') }}</span>
            </label>
        </div>
    </div>


    <div class="md:col-span-2">
        <x-input-label for="address" :value="__('Address')"/>
        <x-text-input wire:model="form.address" id="address" name="address" type="text"
                      class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('form.address')"/>
    </div>

</x-modal-base>
