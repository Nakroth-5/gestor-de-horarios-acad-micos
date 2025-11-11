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
            <div class="space-y-4">
                {{-- Horario --}}
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                @endphp
                @for($i = 0; $i < 6; $i++)
                    <x-container-second-div>
                        <x-input-label for="day_{{ $i }}" :value="__($days[$i])"/>

                        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="schedule_{{ $i }}" :value="__('Schedule')"/>
                                <x-select-input
                                    name="schedules.{{ $i }}.day_schedule_id"
                                    wire:model="schedules.{{ $i }}.day_schedule_id"
                                >
                                    <option value="">{{ __('Select Schedule') }}</option>
                                    @foreach($allDaySchedule->where('day_id', $i + 1) as $daySchedule)
                                        <option value="{{ $daySchedule->id }}">
                                            {{ date('H:i', strtotime($daySchedule->schedule->start)) }} -
                                            {{ date('H:i', strtotime($daySchedule->schedule->end)) }}

                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>

                            <div>
                                <x-input-label for="classroom_{{ $i }}" :value="__('Classroom')"/>
                                <x-select-input
                                    wire:model="schedules.{{ $i }}.classroom_id"
                                    id="classroom_{{ $i }}"
                                >
                                    <option value="">{{ __('Select Classroom') }}</option>
                                    @foreach($allClassroom as $classroom)
                                        <option value="{{ $classroom->id }}">
                                            Aula {{ $classroom->number }} -
                                            Módulo {{ $classroom->module->code ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error class="mt-2"
                                               :messages="$errors->get('schedules.' . $i . '.classroom_id')"/>
                            </div>
                        </div>
                    </x-container-second-div>

                @endfor
            </div>
        </div>

        @if (session()->has('error'))
            <div class="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
                {{ session('error') }}
            </div>
    @endif
</x-modal-base>
