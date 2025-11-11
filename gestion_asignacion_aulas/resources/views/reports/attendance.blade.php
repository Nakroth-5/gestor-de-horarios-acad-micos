<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Asistencia por Docente y Grupo') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver a Reportes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
       
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filtros -->
            <x-container-second-div>
                <div class="p-6">
                    <h1 class="text-gray-700 dark:text-gray-300 mb-4"> Filtros </h1>
                    <form method="GET" action="{{ route('reports.attendance') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <x-input-label for="user_id">Docente</x-input-label>
                            <x-select-input name="user_id" id="user_id">
                                <option value="">Todos los docentes</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $userId == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        <div>
                            <x-input-label for="group_id">Grupo</x-input-label>
                            <x-select-input name="group_id" id="group_id">
                                <option value="">Todos los grupos</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        <div>
                            <x-input-label for="start_date">Fecha Inicio</x-input-label>
                            <x-text-input type="date" name="start_date" id="start_date" value="{{ $startDate }}"/>
                        </div>

                        <div>
                            <x-input-label for="end_date">Fecha Fin</x-input-label>
                            <x-text-input type="date" name="end_date" id="end_date" value="{{ $endDate }}"/>
                        </div>

                        <div class="flex items-end mb-2">
                            <x-primary-button type="submit">
                                Filtrar
                            </x-primary-button>
                        </div>
                    </form>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('reports.attendance', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.pdf/>
                            Descargar PDF
                        </a>
                        <a href="{{ route('reports.attendance.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.excel/>
                            Exportar Excel
                        </a>
                    </div>
                </div>
            </x-container-second-div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-700 dark:text-gray-300">Total de Registros</div>
                        <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $statistics['total'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-1">Presentes</div>
                        <div class="text-2xl font-bold text-green-600">{{ $statistics['present'] }}</div>
                        <div class="text-xs text-gray-700 dark:text-gray-300 mt-1">{{ $statistics['present_percentage'] }}%</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-1">Ausentes</div>
                        <div class="text-2xl font-bold text-red-600">{{ $statistics['absent'] }}</div>
                        <div class="text-xs text-gray-700 dark:text-gray-300 mt-1">{{ $statistics['absent_percentage'] }}%</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-1">Tardanzas</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $statistics['late'] }}</div>
                        <div class="text-xs text-gray-700 dark:text-gray-300 mt-1">{{ $statistics['late_percentage'] }}%</div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Registros -->
            <x-container-second-div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Registros de Asistencia</h3>

                    @if($attendanceRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Docente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Materia</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grupo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora Marcado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($attendanceRecords as $record)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                {{ $record->user->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                <div class="font-medium">{{ $record->assignment->userSubject->subject->name }}</div>
                                                <div class="text-gray-500 dark:text-gray-400">{{ $record->assignment->userSubject->subject->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ $record->assignment->group->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($record->assignment->daySchedule->schedule->start)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($record->assignment->daySchedule->schedule->end)->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($record->status === 'present')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                        Presente
                                                    </span>
                                                @elseif($record->status === 'late')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                        Tardanza
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                        Ausente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ $record->scan_time ? \Carbon\Carbon::parse($record->scan_time)->format('H:i:s') : 'NO MARCO' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">No se encontraron registros de asistencia con los filtros seleccionados.</p>
                        </div>
                    @endif
                </div>
            </x-container-second-div>
        </div>
    </div>
</x-app-layout>
