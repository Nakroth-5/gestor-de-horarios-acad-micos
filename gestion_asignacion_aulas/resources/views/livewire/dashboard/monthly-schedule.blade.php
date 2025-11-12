<div>
    <x-container-second-div>
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {{ $showDaySchedule ? 'Horarios del Día' : 'Calendario Mensual de Horarios' }}
                </h3>
                @if($showDaySchedule)
                    <button wire:click="closeDaySchedule"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        ← Volver al Calendario
                    </button>
                @endif
            </div>

            <!-- Filtros y navegación -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <!-- Filtro Docente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Docente
                    </label>
                    <select wire:model.live="filterDocente"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Grupo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Grupo
                    </label>
                    <select wire:model.live="filterGrupo"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Aula -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Aula
                    </label>
                    <select wire:model.live="filterAula"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}">Aula {{ $aula->number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Navegación de meses -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mes
                    </label>
                    <div class="flex gap-2">
                        <button wire:click="previousMonth"
                                wire:loading.attr="disabled"
                                class="flex-1 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="previousMonth">←</span>
                            <span wire:loading wire:target="previousMonth">...</span>
                        </button>
                        <button wire:click="currentMonth"
                                wire:loading.attr="disabled"
                                class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm disabled:opacity-50">
                            <span wire:loading.remove wire:target="currentMonth">Hoy</span>
                            <span wire:loading wire:target="currentMonth">...</span>
                        </button>
                        <button wire:click="nextMonth"
                                wire:loading.attr="disabled"
                                class="flex-1 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="nextMonth">→</span>
                            <span wire:loading wire:target="nextMonth">...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Indicador de mes actual -->
            <div class="text-sm text-gray-600 dark:text-gray-400 capitalize">
                {{ $currentMonth->locale('es')->isoFormat('MMMM YYYY') }}
                @if($showDaySchedule)
                    - {{ \Carbon\Carbon::parse($selectedDate)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                @endif
            </div>
        </div>

        @if(!$showDaySchedule)
            <!-- Calendario Mensual -->
            <div class="overflow-x-auto">
                <div class="min-w-full">
                    <!-- Días de la semana (header) -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Lun</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Mar</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Mié</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Jue</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Vie</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Sáb</div>
                        <div class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300 py-2">Dom</div>
                    </div>

                    <!-- Días del calendario -->
                    @foreach($weeks as $week)
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            @foreach($week as $day)
                                @php
                                    $isSelected = $selectedDate && \Carbon\Carbon::parse($selectedDate)->isSameDay($day['date']);
                                @endphp
                                <div
                                    wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')"
                                    class="min-h-[80px] p-2 border rounded-lg cursor-pointer transition-all hover:shadow-md
                                        {{ $day['isToday'] ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700' }}
                                        {{ !$day['isCurrentMonth'] ? 'opacity-40' : '' }}
                                        {{ $isSelected ? 'ring-2 ring-blue-500 bg-blue-100 dark:bg-blue-900/30' : 'bg-white dark:bg-gray-900' }}
                                        {{ $day['assignmentsCount'] > 0 ? 'hover:border-blue-400' : '' }}">

                                    <!-- Número del día -->
                                    <div class="text-xs font-medium {{ $day['isToday'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }} mb-1">
                                        {{ $day['date']->day }}
                                    </div>

                                    <!-- Indicador de clases -->
                                    @if($day['assignmentsCount'] > 0)
                                        <div class="text-[10px] text-center">
                                            <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                {{ $day['assignmentsCount'] }} clase{{ $day['assignmentsCount'] > 1 ? 's' : '' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                <strong>Tip:</strong> Haz clic en un día para ver los horarios detallados en formato tabla
            </div>

        @else
            <!-- Tabla de Horarios del Día -->
            @if($daySchedule && $daySchedule->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Horario</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Materia</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Docente</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grupo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aula</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($daySchedule as $assignment)
                                @php
                                    $status = $this->getAttendanceStatus($assignment);
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ substr($assignment->daySchedule->schedule->start, 0, 5) }} -
                                        {{ substr($assignment->daySchedule->schedule->end, 0, 5) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $assignment->userSubject->subject->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $assignment->userSubject->subject->code }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $assignment->userSubject->user->name }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $assignment->group->name }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        Aula {{ $assignment->classroom->number }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status['bgColor'] }} {{ $status['color'] }}">
                                            @if($status['status'] === 'on_time')
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No hay clases programadas para este día</p>
                </div>
            @endif
        @endif
    </x-container-second-div>
</div>
