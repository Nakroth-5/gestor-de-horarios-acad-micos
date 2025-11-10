<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Asignación' : 'Crear Asignación'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
    max-width="4xl"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Docente-Materia --}}
        <div class="md:col-span-2">
            <x-input-label for="user_subject_id" :value="__('Docente - Materia')"/>
            <select
                wire:model="form.user_subject_id"
                id="user_subject_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Teacher and Subject') }}</option>
                @foreach($allUserSubject as $userSubject)
                    <option value="{{ $userSubject->id }}">
                        {{ $userSubject->user->name }} {{ $userSubject->user->last_name }} -
                        {{ $userSubject->subject->code }} - {{ $userSubject->subject->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.user_subject_id')"/>
        </div>

        {{-- Grupo --}}
        <div>
            <x-input-label for="group_id" :value="__('Group')"/>
            <select
                wire:model="form.group_id"
                id="group_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Group') }}</option>
                @foreach($allGroups as $group)
                    <option value="{{ $group->id }}">
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.group_id')"/>
        </div>

        {{-- Gestión Académica --}}
        <div>
            <x-input-label for="academic_id" :value="__('Academic Period')"/>
            <select
                wire:model="form.academic_id"
                id="academic_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Period (Optional)') }}</option>
                @foreach($allAcademic as $academic)
                    <option value="{{ $academic->id }}">{{ $academic->name ?? $academic->id }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.academic_id')"/>
        </div>

        {{-- Horarios con aulas individuales --}}
        <div class="md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <x-input-label :value="__('Schedules with Classrooms (up to 3)')"/>
                <button type="button" wire:click="addSchedule" class="text-sm text-blue-600 hover:text-blue-800">
                    + Agregar otro horario
                </button>
            </div>

            <div class="space-y-4">
                @foreach($schedules as $index => $schedule)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Horario {{ $index + 1 }}
                            </span>
                            @if($index > 0)
                                <button type="button"
                                        wire:click="removeSchedule({{ $index }})"
                                        class="text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Horario --}}
                            <div>
                                <x-select-input name="schedule_id_lunes">

                                    @foreach($allDaySchedule->where('day_id', 1) as $daySchedule)

                                        {{-- Usamos el ID del 'day_schedule' como valor --}}
                                        <option value="{{ $daySchedule->id }}">

                                            {{--
                                              Accedemos a la relación 'schedule' para obtener
                                              la hora de inicio y fin.
                                            --}}
                                            {{ date('H:i', strtotime($daySchedule->schedule->start)) }} -
                                            {{ date('H:i', strtotime($daySchedule->schedule->end)) }}

                                        </option>
                                    @endforeach

                                </x-select-input>

                                <x-select-input name="schedule_id_lunes">

                                    @foreach($allDaySchedule->where('day_id', 2) as $daySchedule)

                                        {{-- Usamos el ID del 'day_schedule' como valor --}}
                                        <option value="{{ $daySchedule->id }}">

                                            {{--
                                              Accedemos a la relación 'schedule' para obtener
                                              la hora de inicio y fin.
                                            --}}
                                            {{ date('H:i', strtotime($daySchedule->schedule->start)) }} -
                                            {{ date('H:i', strtotime($daySchedule->schedule->end)) }}

                                        </option>
                                    @endforeach

                                </x-select-input>
                            </div>

                            {{-- Aula para este horario --}}
                            <div>
                                <x-input-label for="classroom_{{ $index }}" :value="__('Classroom')"/>
                                <select
                                    wire:model="schedules.{{ $index }}.classroom_id"
                                    id="classroom_{{ $index }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('Select Classroom') }}</option>
                                    @foreach($allClassroom as $classroom)
                                        <option value="{{ $classroom->id }}">
                                            Aula {{ $classroom->number }} -
                                            Módulo {{ $classroom->module->code ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2"
                                               :messages="$errors->get('schedules.' . $index . '.classroom_id')"/>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
            {{ session('error') }}
        </div>
    @endif
</x-modal-base>
