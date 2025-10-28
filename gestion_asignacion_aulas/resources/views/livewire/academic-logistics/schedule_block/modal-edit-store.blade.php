<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Bloque de Horario' : 'Crear Bloque de Horario'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- SELECT DE HORARIO (Schedule completo) -->
        <div class="md:col-span-2">
            <x-input-label for="schedule_id" :value="__('Schedule')"/>
            <x-select-input 
                wire:model.live="form.schedule_id" 
                id="schedule_id"                 
                class="mt-1 block w-full" 
                required autofocus
            >
                <option value="">{{ __('Select a Schedule') }}</option>
                @foreach($this->allSchedules as $schedule)
                    <option value="{{ $schedule->id }}">
                        {{ $schedule->start }} - {{ $schedule->end }}
                    </option>
                @endforeach
                <option value="new">{{ __('Create New Schedule') }}</option>
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('form.schedule_id')"/>
        </div>

        <!-- CAMPOS PARA NUEVO HORARIO -->
        @if($this->form->schedule_id === 'new')
            <div>
                <x-input-label for="schedule_start" :value="__('Start Time')"/>
                <x-text-input 
                    type="time"
                    wire:model="form.schedule_start"
                    id="schedule_start"
                    class="mt-1 block w-full" 
                    required autofocus
                />
                <x-input-error class="mt-2" :messages="$errors->get('form.schedule_start')"/>
            </div>

            <div>
                <x-input-label for="schedule_end" :value="__('End Time')"/>
                <x-text-input 
                    type="time"
                    wire:model="form.schedule_end"
                    id="schedule_end"
                    class="mt-1 block w-full" 
                    required autofocus
                />
                <x-input-error class="mt-2" :messages="$errors->get('form.schedule_end')"/>
            </div>
        @endif
        <!-- SELECT MÚLTIPLE DE DÍAS -->
        <div class="md:col-span-2">
            <x-input-label for="day_ids" :value="__('Days')" />
            
            @if($this->editing)
                <!-- MODO EDICIÓN: solo un día -->
                <x-select-input
                    wire:model="form.day_ids.0"
                    id="day_ids"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-sm"
                    required
                >
                    <option value="">{{ __('Select a day') }}</option>
                    @foreach($this->allDays as $day)
                        <option value="{{ $day->id }}">{{ $day->name }}</option>
                    @endforeach
                </x-select-input>

                <p class="mt-1 text-sm text-gray-400">
                    {{ __('Select the day for this schedule block') }}
                </p>

            @else
                <!-- MODO CREACIÓN: múltiples días -->
                <div class="mt-2 bg-gray-800 border border-gray-700 rounded-md p-3 shadow-sm max-h-48 overflow-y-auto space-y-2">
                    @foreach($this->allDays as $day)
                        <label 
                            class="flex items-center gap-3 p-2 rounded-md cursor-pointer transition-colors group
                                {{ in_array($day->id, $this->form->day_ids) 
                                    ? 'bg-indigo-900 text-white' 
                                    : 'hover:bg-gray-700 text-gray-200' }}"
                        >

                            <input
                                type="checkbox" 
                                wire:model="form.day_ids" 
                                value="{{ $day->id }}"
                                class="w-5 h-5 rounded border-2 border-gray-500 text-indigo-600 
                                    focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0
                                    bg-transparent cursor-pointer transition-colors
                                    checked:bg-indigo-600 checked:border-indigo-600"
                            >
                            
                            <span class="text-sm font-medium select-none">{{ $day->name }}</span>
                        </label>
                    @endforeach
                </div>

                <p class="mt-1 text-sm text-gray-400">
                    {{ __('Select one or more days to create schedule blocks') }}
                </p>
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('form.day_ids')" />
        </div>



    </div>
</x-modal-base>