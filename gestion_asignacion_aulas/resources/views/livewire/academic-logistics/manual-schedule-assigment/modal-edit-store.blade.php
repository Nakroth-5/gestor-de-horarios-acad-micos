<x-modal-base
    :show="$show"
    :title="$editing ? 'Editar Asignación' : 'Crear Asignación'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
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

        {{-- Aula --}}
        <div>
            <x-input-label for="classroom_id" :value="__('Classroom')"/>
            <select
                wire:model="form.classroom_id"
                id="classroom_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Classroom') }}</option>
                @foreach($allClassroom as $classroom)
                    <option value="{{ $classroom->id }}">
                        Aula {{ $classroom->number }} - Módulo {{ $classroom->module->code ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.classroom_id')"/>
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

        {{-- Horario --}}
        <div class="md:col-span-2">
            <x-input-label for="day_schedule_id" :value="__('Schedule')"/>
            <select
                wire:model="form.day_schedule_id"
                id="day_schedule_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Schedule') }}</option>
                @foreach($allDaySchedule as $schedule)
                    <option value="{{ $schedule->id }}">
                        {{ $schedule->day->name ?? 'N/A' }}:
                        {{ date('H:i', strtotime($schedule->schedule->start)) }} -
                        {{ date('H:i', strtotime($schedule->schedule->end)) }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('form.day_schedule_id')"/>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
            {{ session('error') }}
        </div>
    @endif
</x-modal-base>
