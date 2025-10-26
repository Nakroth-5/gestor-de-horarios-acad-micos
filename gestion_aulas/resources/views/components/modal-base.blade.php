@props([
    'show' => false,
    'title' => '',
    'editing' => null,
    'submitPrevent' => null,
    'clickClose' => null,
    'clickSave' => null
])

@if($show)
    <x-container-div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <x-container-second-div class="max-w-xl w-full max-h-[95vh] overflow-y-auto">
            <x-container-div>
                <div>
                    <x-input-label class="flex justify-between">
                        {{ $title }}
                        <x-secondary-button wire:click="{{ $clickClose }}">
                            <x-icons.close/>
                            {{ __('Cerrar') }}
                        </x-secondary-button>
                    </x-input-label>
                </div>

                <form wire:submit.prevent="{{ $submitPrevent }}">
                    <x-container-div>
                        {{ $slot }}
                    </x-container-div>
                </form>

                <div class="flex justify-end mt-6">
                    <x-secondary-button type="button" wire:click="{{ $clickClose }}" class="mr-3">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <x-primary-button type="button" wire:click="{{ $clickSave }}">
                        <x-icons.save></x-icons.save>
                        {{ $editing ? __('Actualizar') : __('Crear') }}
                    </x-primary-button>
                </div>
            </x-container-div>
        </x-container-second-div>
    </x-container-div>
@endif
