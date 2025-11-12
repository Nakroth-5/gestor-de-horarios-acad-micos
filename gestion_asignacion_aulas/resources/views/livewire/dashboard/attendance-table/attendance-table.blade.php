<div>
    <x-container-second-div>
        <!-- Header con filtros -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Tabla de Asistencia por Docente
            </h3>

            <!-- Filtros -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Buscar
                    </label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar docente, materia, grupo..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                </div>

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

                <!-- Filtro Materia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Materia
                    </label>
                    <select wire:model.live="filterMateria"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id }}">{{ $materia->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botón limpiar filtros -->
            <div class="flex justify-between items-center">
                <button wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpiar filtros
                </button>

                <!-- Selector de registros por página -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Mostrar</span>
                    <select wire:model.live="perPage"
                            class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600 dark:text-gray-400">registros</span>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Docente
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total Sesiones
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Asistencias
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Retrasos
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Inasistencias
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            % Asistencia
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($attendanceRecords as $record)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                            {{ strtoupper(substr($record->docente_name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $record->docente_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">
                                {{ $record->total_sesiones }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-green-600 dark:text-green-400">
                                {{ $record->asistencias }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-yellow-600 dark:text-yellow-400">
                                {{ $record->retrasos }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-red-600 dark:text-red-400">
                                {{ $record->inasistencias }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <div class="w-full max-w-xs">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                <div class="h-2 rounded-full {{ $record->porcentaje_asistencia >= 90 ? 'bg-green-500' : ($record->porcentaje_asistencia >= 75 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                     style="width: {{ $record->porcentaje_asistencia }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $record->porcentaje_asistencia }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button wire:click="showDetails({{ $record->docente_id }})"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-2 text-sm">No se encontraron registros</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $attendanceRecords->links() }}
        </div>
    </x-container-second-div>

    <!-- Modal de detalles -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeModal">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-6xl w-full p-6 max-h-[90vh] overflow-y-auto" wire:click.stop>
                    <!-- Header del modal -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                Historial de Asistencias
                            </h3>
                            @if($selectedDocente)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Docente: <span class="font-medium">{{ $selectedDocente->name }}</span>
                                </p>
                            @endif
                        </div>
                        <button wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Tabla de detalles -->
                    @if($docenteDetails && $docenteDetails->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Materia
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Grupo
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Total Sesiones
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Asistencias
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Retrasos
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Inasistencias
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            % Asistencia
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($docenteDetails as $detail)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $detail->materia_name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $detail->materia_code }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                    {{ $detail->grupo_name }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-gray-100">
                                                {{ $detail->total_sesiones }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-medium text-green-600 dark:text-green-400">
                                                {{ $detail->asistencias }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-medium text-yellow-600 dark:text-yellow-400">
                                                {{ $detail->retrasos }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-medium text-red-600 dark:text-red-400">
                                                {{ $detail->inasistencias }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-full max-w-xs">
                                                        <div class="flex items-center">
                                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                                <div class="h-2 rounded-full {{ $detail->porcentaje_asistencia >= 90 ? 'bg-green-500' : ($detail->porcentaje_asistencia >= 75 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                                     style="width: {{ $detail->porcentaje_asistencia }}%"></div>
                                                            </div>
                                                            <span class="text-xs font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $detail->porcentaje_asistencia }}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No hay datos disponibles</p>
                        </div>
                    @endif

                    <!-- Footer del modal -->
                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeModal"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
