<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Horarios Semanales') }}
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
                    <form method="GET" action="{{ route('reports.weekly-schedules') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="academic_management_id">Periodo Académico</x-input-label>
                            <x-select-input name="academic_management_id" id="academic_management_id">
                                <option value="">Periodo Activo</option>
                                @foreach($academicManagements as $am)
                                    <option value="{{ $am->id }}" {{ request('academic_management_id') == $am->id ? 'selected' : '' }}>
                                        {{ $am->name }} ({{ \Carbon\Carbon::parse($am->start_date)->format('Y') }})
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

                        <div class="flex items-end mb-2">
                            <x-primary-button type="submit">
                                Filtrar
                            </x-primary-button>
                        </div>
                    </form>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('reports.weekly-schedules', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.pdf/>
                            Descargar PDF
                        </a>
                        <a href="{{ route('reports.weekly-schedules.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <x-icons.excel/>
                            Exportar Excel
                        </a>
                    </div>
                </div>
            </x-container-second-div>

            <!-- Tabla de Horarios -->
            <x-container-second-div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Horario - {{ $academicManagement->name }}
                        @if($groupId)
                            - {{ $groups->firstWhere('id', $groupId)->name }}
                        @endif
                    </h3>

                    @if(count($scheduleData) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <x-table.td>Día</x-table.td>
                                        <x-table.td>Horario</x-table.td>
                                        <x-table.td>Materia</x-table.td>
                                        <x-table.td>Docente</x-table.td>
                                        <x-table.td>Grupo</x-table.td>
                                        <x-table.td>Aula</x-table.td>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($scheduleData as $day => $assignments)
                                        @foreach($assignments as $index => $assignment)
                                            <tr class="hover:bg-gray-50">
                                                @if($index === 0)
                                                    <td rowspan="{{ count($assignments) }}" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 align-top">
                                                        {{ __($day) }}
                                                    </td>
                                                @endif
                                                <x-table.td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i') }}
                                                </x-table.td>
                                                <x-table.td class="px-6 py-4 text-sm text-gray-900">
                                                    <div class="font-medium">{{ $assignment->userSubject->subject->name }}</div>
                                                    <div class="text-gray-500">{{ $assignment->userSubject->subject->code }}</div>
                                                </x-table.td>
                                                <x-table.td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $assignment->userSubject->user->name }}
                                                </x-table.td>
                                                <x-table.td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $assignment->group->name }}
                                                </x-table.td>
                                                <x-table.td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $assignment->classroom->name }}
                                                    <div class="text-gray-500 text-xs">{{ $assignment->classroom->infrastructure->name }}</div>
                                                </x-table.td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">No se encontraron horarios con los filtros seleccionados.</p>
                        </div>
                    @endif
                </div>
            </x-container-second-div>
        </div>
    </div>
</x-app-layout>
