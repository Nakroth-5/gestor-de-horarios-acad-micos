@props([
    'title' => '',
    'modeLive' => null,
    'search' => null,
    'clickClearSearch' => null,
    'clickOpenCreateModal' => null,
    'btnName' => '',
])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <x-container-second-div>
        <div class="flex items-center ml-4 space-x-2">

            <x-icons.roles class="w-5 h-5 text-slate-600 dark:text-slate-400"/>

            <span class="text-lg font-semibold text-slate-600 dark:text-slate-400">
            {{ $title }}
        </span>
        </div>
    </x-container-second-div>

    <x-container-second-div>
        <div class="flex items-center space-x-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-icons.search class="h-5 w-5 text-slate-400 dark:text-slate-500"></x-icons.search>
                </div>
                <x-text-input
                    wire:model.live="{{ $modeLive }}"
                    type="text"
                    placeholder="{{ __('Buscar por nombre, ...') }}"
                    class="pl-10 pr-10"
                />
                @if($search)
                    <button
                        wire:click="{{ $clickClearSearch }}"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-red-500 transition-colors">
                        <x-icons.close class="h-5 w-5 text-slate-400 dark:text-slate-500"></x-icons.close>
                    </button>
                @endif
            </div>
        </div>
    </x-container-second-div>

    @if($btnName)
        <x-container-second-div>
            <x-primary-button
                wire:click="{{ $clickOpenCreateModal }}"
                class="flex items-center w-full justify-center">
                <x-icons.user class="mr-2"/>
                {{ $btnName }}
            </x-primary-button>
        </x-container-second-div>
    @else
        <x-container-second-div>
            <x-primary-button class="items-center w-full justify-center"/> </x-container-second-div>
    @endif
</div>

{{-- Contador de resultados (Sin cambios) --}}
@if($search)
    <div class="mb-4">
        <x-container-second-div>
            <div class="flex items-center justify-between p-3">
                <span class="text-sm text-slate-600 dark:text-slate-400">
                    <x-icons.search class="inline mr-1"></x-icons.search>
                    Resultados para:
                    <strong class="text-indigo-500 dark:text-indigo-400">"{{ $search }}"</strong>
                </span>
                <button
                    wire:click="{{ $clickClearSearch }}"
                    class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-500 dark:hover:text-blue-400 underline">
                    Limpiar búsqueda
                </button>
            </div>
        </x-container-second-div>
    </div>
@endif
