<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Materia' : 'Crear Materia'"
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
            <x-input-error class="mt-2" :messages="$errors->get('form.code')"/>
        </div>
        <div>
            <x-input-label for="credits" :value="__('Credits')"/>
            <x-text-input wire:model="form.credits" id="credits" name="credits" type="number"
                        class="mt-1 block w-full" required autofocus/>
            <x-input-error class="mt-2" :messages="$errors->get('form.credits')"/>
        </div>

    <div class="md:col-span-2">
            <x-input-label for="name" :value="__('Name')"/>
            <x-text-input wire:model="form.name" id="name" name="name" type="text" class="mt-1 block w-full" required
                          autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('form.name')"/>
    </div>
</x-modal-base>
