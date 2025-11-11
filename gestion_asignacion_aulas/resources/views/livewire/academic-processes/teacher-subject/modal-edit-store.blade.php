<x-modal-base
    :show="$show"
    :title="'Asignar Materias al Docente'"
    :editing="$editing"
    submit-prevent="save"
    click-close="closeModal"
    click-save="save"
>
    <div class="grid grid-cols-1 gap-6">

        <!-- INFORMACIÓN DEL DOCENTE -->
        @if($editing)
            @php
                $teacher = \App\Models\User::find($editing);
            @endphp

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg">
                        {{ substr($teacher->name ?? '', 0, 1) }}{{ substr($teacher->last_name ?? '', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg">
                            {{ $teacher->name }} {{ $teacher->last_name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $teacher->email }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- SELECT MÚLTIPLE DE MATERIAS -->
        <div>
            <x-input-label for="subject_ids" :value="__('Materias')" />

            <div class="mt-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md p-3 shadow-sm max-h-80 overflow-y-auto space-y-2">
                @forelse(\App\Models\Subject::where('is_active', true)->orderBy('name')->get() as $subject)
                    <label
                        class="flex items-start gap-3 p-3 rounded-md cursor-pointer transition-colors group
                            {{ in_array($subject->id, $this->form->subject_ids)
                                ? 'bg-indigo-100 dark:bg-indigo-900 border-2 border-indigo-400 dark:border-indigo-600'
                                : 'hover:bg-gray-200 dark:hover:bg-gray-700 border-2 border-transparent' }}"
                    >
                        <x-checkbox
                            wire:model="form.subject_ids"
                            value="{{ $subject->id }}"
                            class="mt-1"
                        />

                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-900 dark:text-white block">
                                {{ $subject->name }}
                            </span>
                            <div class="flex gap-3 mt-1">
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    Código: <span class="font-mono">{{ $subject->code }}</span>
                                </span>
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    Créditos: {{ $subject->credits }}
                                </span>
                            </div>
                            
                            @if(in_array($subject->id, $this->form->subject_ids))
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Carrera Universitaria
                                    </label>
                                    <select wire:model="form.subject_careers.{{ $subject->id }}"
                                            class="w-full text-xs px-2 py-1 border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">-- Seleccione una carrera --</option>
                                        @foreach(\App\Models\UniversityCareer::orderBy('name')->get() as $career)
                                            <option value="{{ $career->id }}">
                                                {{ $career->code }} - {{ $career->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </label>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                        No hay materias disponibles
                    </p>
                @endforelse
            </div>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Seleccione una o más materias para asignar al docente') }}
            </p>

            <x-input-error class="mt-2" :messages="$errors->get('form.subject_ids')" />
        </div>

    </div>
</x-modal-base>
