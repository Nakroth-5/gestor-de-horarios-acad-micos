<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Aulas Disponibles') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver a Reportes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-container-second-div>
                <div class="p-6">
                    <h1 class="text-gray-700 dark:text-gray-300 mb-4">Filtros</h1>
                    <form method="GET" action="{{ route('reports.available-classrooms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="date">Fecha</x-input-label>
                            <x-text-input type="date" name="date" id="date" value="{{ $date }}" class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label for="day_id">Día</x-input-label>
                            <x-select-input name="day_id" id="day_id">
                                <option value="">Todos los días</option>
                                @foreach($days as $day)
                                    <option value="{{ $day->id }}" {{ $dayId == $day->id ? 'selected' : '' }}>
                                        {{ __($day->name) }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        <div>
                            <x-input-label for="schedule_id">Bloque Horario</x-input-label>
                            <x-select-input name="schedule_id" id="schedule_id">
                                <option value="">Todos los horarios</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ $scheduleId == $schedule->id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($schedule->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end)->format('H:i') }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        <div class="flex items-end gap-2">
                            <x-primary-button type="submit">Filtrar</x-primary-button>
                        </div>
                    </form>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('reports.available-classrooms', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.pdf class="w-4 h-4 mr-2"/>
                            Descargar PDF
                        </a>
                        <a href="{{ route('reports.available-classrooms.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.excel class="w-4 h-4 mr-2"/>
                            Exportar Excel
                        </a>
                    </div>
                </div>
            </x-container-second-div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Aulas Disponibles</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $availableClassrooms->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Aulas Ocupadas</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $occupiedClassroomsData->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aulas Disponibles -->
            <x-container-second-div class="mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-green-700 dark:text-green-400">✅ Aulas Disponibles</h3>

                    @if($availableClassrooms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($availableClassrooms as $classroom)
                                <div class="border border-green-200 dark:border-green-700 bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $classroom->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $classroom->infrastructure->name }}</p>
                                            <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Capacidad: {{ $classroom->capacity }}
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            Disponible
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No hay aulas disponibles con los filtros seleccionados.</p>
                        </div>
                    @endif
                </div>
            </x-container-second-div>

            <!-- Aulas Ocupadas -->
            <x-container-second-div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-red-700 dark:text-red-400">🔒 Aulas Ocupadas</h3>

                    @if($occupiedClassroomsData->count() > 0)
                        <div class="space-y-4">
                            @foreach($occupiedClassroomsData as $classroom)
                                @php
                                    $assignment = $occupiedDetails[$classroom->id] ?? null;
                                @endphp
                                <div class="border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $classroom->name }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $classroom->infrastructure->name }}</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                    Ocupada
                                                </span>
                                            </div>
                                            
                                            @if($assignment)
                                                <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-700">
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Materia:</span>
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $assignment->userSubject->subject->name }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Docente:</span>
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $assignment->userSubject->user->name }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Grupo:</span>
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $assignment->group->name }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Horario:</span>
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Todas las aulas están disponibles.</p>
                        </div>
                    @endif
                </div>
            </x-container-second-div>
        </div>
    </div>
</x-app-layout>
