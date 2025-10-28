<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Aula' : 'Crear Aula'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="module_id" :value="__('Module')"/>
            <select
                wire:model="form.module_id"
                id="module_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Module') }}</option>
                @foreach($relations as $module)
                    <option value="{{ $module->id }}">{{ $module->code }}</option> {{-- FIX 2: Use module->id --}}
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.module_id')"/> {{-- FIX 3: Match the wire:model --}}
        </div>

        <div class="flex items-center justify-start pt-6">
            <label for="form.is_active" class="flex items-center">
                <x-checkbox wire:model="form.is_active" id="is_active" name="is_active"/>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active Module') }}</span>
            </label>
        </div>

        <div>
            <x-input-label for="type" :value="__('Type')"/>
            <select
                wire:model="form.type"
                id="type"
                class="mt-1 block w-full  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                @php
                    $types = ['aula', 'laboratorio pcs', 'auditorio', 'biblioteca', 'laboratorio fisica'];
                @endphp
                <option value="">{{ __('Select Type') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.type')"/>
        </div>

        <div class="md:col-span-2">
            <x-input-label for="number" :value="__('Number')"/>
            <x-text-input wire:model="form.number" id="number" name="number" type="number"
                          class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.number')"/>
        </div>

        <div class="md:col-span-2">
            <x-input-label for="capacity" :value="__('Capacity')"/>
            <x-text-input wire:model="form.capacity" id="capacity" name="capacity" type="number"
                          class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.capacity')"/>
        </div>
    </div>
</x-modal-base>
