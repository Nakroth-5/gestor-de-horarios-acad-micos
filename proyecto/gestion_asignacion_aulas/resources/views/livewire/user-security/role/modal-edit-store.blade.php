<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Rol' : 'Crear Rol'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Nombre del Rol')"/>
                <x-text-input wire:model="form.name" id="name" name="name" type="text" class="mt-1 block w-full"
                              required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('form.name')"/>
            </div>

            <div>
                <x-input-label for="level" :value="__('Nivel (Prioridad)')"/>
                <x-text-input wire:model="form.level" id="level" name="level" type="number" min="1"
                              class="mt-1 block w-full" required/>
                <x-input-error class="mt-2" :messages="$errors->get('form.level')"/>
            </div>

            <div class="flex items-center justify-start pt-4">
                <label for="form.is_active" class="flex items-center">
                    <x-checkbox wire:model="form.is_active" id="is_active" name="is_active"/>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Rol Activo') }}</span>
                </label>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <x-input-label for="description" :value="__('Descripción')"/>
                <textarea
                    wire:model="form.description"
                    id="description"
                    rows="4"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                ></textarea>
                <x-input-error class="mt-2" :messages="$errors->get('form.description')"/>
            </div>

            <div>
                <x-input-label for="permissions" :value="__('Permisos')"/>
                <x-table.select
                    wire:model="form.permissions"
                    id="permissions"
                    multiple
                    class="mt-1 block w-full h-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach($relations as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                    @endforeach
                </x-table.select>
                <x-input-error class="mt-2" :messages="$errors->get('form.permissions')"/>
            </div>
        </div>
    </div>
</x-modal-base>
