<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Asignación' : 'Crear Asignación'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
    max-width="6xl"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Docente-Materia --}}
        <div class="md:col-span-2">
            <x-input-label for="user_subject_id" :value="__('Docente - Materia')"/>
            <x-select-input
                wire:model="form.user_subject_id"
                id="user_subject_id"
            >
                <option value="">{{ __('Select Teacher and Subject') }}</option>
                @foreach($allUserSubject as $userSubject)
                    <option value="{{ $userSubject->id }}">
                        {{ $userSubject->user->name }} {{ $userSubject->user->last_name }} -
                        {{ $userSubject->subject->code }} - {{ $userSubject->subject->name }}
                    </option>
                @endforeach
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('form.user_subject_id')"/>
        </div>

        {{-- Grupo --}}
        <div>
            <x-input-label for="group_id" :value="__('Group')"/>
            <x-select-input
                wire:model="form.group_id"
                id="group_id"
            >
                <option value="">{{ __('Select Group') }}</option>
                @foreach($allGroups as $group)
                    <option value="{{ $group->id }}">
                        {{ $group->name }}
                    </option>
                @endforeach
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('form.group_id')"/>
        </div>

        {{-- Gestión Académica --}}
        <div>
            <x-input-label for="academic_id" :value="__('Academic Period')"/>
            <x-select-input
                wire:model="form.academic_id"
                id="academic_id"
            >
                <option value="">{{ __('Select Period') }}</option>
                @foreach($allAcademic as $academic)
                    <option value="{{ $academic->id }}">{{ $academic->name ?? $academic->id }}</option>
                @endforeach
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('form.academic_id')"/>
        </div>


        {{-- Información sobre selección de horarios --}}
        <div class="md:col-span-2">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">
                            Instrucciones para Asignar Horarios
                        </p>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1 list-disc list-inside">
                            <li>Seleccione los días que desee para la materia (no es obligatorio seleccionar todos)</li>
                            <li>Para cada día que elija, debe seleccionar TANTO el horario COMO el aula</li>
                            <li>Solo se guardarán los días con horario y aula completos</li>
                            <li>Si deja algún campo vacío en un día, ese día no se guardará</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Horarios para todos los días de la semana --}}
        <div class="md:col-span-2">
            <x-input-label :value="__('Horarios por Día')" class="mb-4 text-lg font-semibold"/>

            <div class="space-y-4">
                @foreach($daysOfWeek as $day)
                    @php
                        $schedulesForDay = $this->getSchedulesForDay($day);
                        $dayNameSpanish = $this->getDayNameInSpanish($day);
                        $hasSchedule = !empty($schedules[$day]['day_schedule_id']) && !empty($schedules[$day]['classroom_id']);
                    @endphp

                    <div class="border rounded-lg p-4 transition-all
                        {{ $hasSchedule
                            ? 'border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20'
                            : 'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800'
                        }}"
                         wire:key="schedule-{{ $day }}">

                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold {{ $hasSchedule ? 'text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $dayNameSpanish }}
                                </span>
                                @if($hasSchedule)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Completo
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $schedulesForDay->count() }} horarios disponibles
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Horario filtrado por día --}}
                            <div>
                                <x-input-label for="schedule_{{ $day }}" :value="__('Horario')" class="text-xs"/>
                                <x-select-input
                                    wire:model="schedules.{{ $day }}.day_schedule_id"
                                    id="schedule_{{ $day }}"
                                    class="mt-1"
                                >
                                    <option value="">Seleccionar horario (opcional)</option>
                                    @forelse($schedulesForDay as $daySchedule)
                                        <option value="{{ $daySchedule->id }}">
                                            {{ date('H:i', strtotime($daySchedule->schedule->start)) }} -
                                            {{ date('H:i', strtotime($daySchedule->schedule->end)) }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No hay horarios para este día</option>
                                    @endforelse
                                </x-select-input>
                            </div>

                            {{-- Aula para este horario --}}
                            <div>
                                <x-input-label for="classroom_{{ $day }}" :value="__('Aula')" class="text-xs"/>
                                <x-select-input
                                    wire:model="schedules.{{ $day }}.classroom_id"
                                    id="classroom_{{ $day }}"
                                    class="mt-1"
                                >
                                    <option value="">Seleccionar aula (opcional)</option>
                                    @foreach($allClassroom as $classroom)
                                        <option value="{{ $classroom->id }}">
                                            Aula {{ $classroom->number }} - Módulo {{ $classroom->module->code ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                        </div>

                        {{-- Advertencia si solo uno está seleccionado --}}
                        @if((!empty($schedules[$day]['day_schedule_id']) && empty($schedules[$day]['classroom_id'])) ||
                            (empty($schedules[$day]['day_schedule_id']) && !empty($schedules[$day]['classroom_id'])))
                            <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded text-xs text-yellow-700 dark:text-yellow-300">
                                ⚠️ Debe seleccionar tanto el horario como el aula para que este día se guarde
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Resumen de días seleccionados --}}
        @php
            $selectedDaysCount = 0;
            foreach($schedules as $day => $schedule) {
                if(!empty($schedule['day_schedule_id']) && !empty($schedule['classroom_id'])) {
                    $selectedDaysCount++;
                }
            }
        @endphp

        @if($selectedDaysCount > 0)
            <div class="md:col-span-2">
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm font-medium text-green-900 dark:text-green-100">
                        📅 {{ $selectedDaysCount }} {{ $selectedDaysCount === 1 ? 'día' : 'días' }} seleccionado(s) para guardar
                    </p>
                </div>
            </div>
        @endif
    </div>

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
            {{ session('error') }}
        </div>
    @endif
</x-modal-base>
