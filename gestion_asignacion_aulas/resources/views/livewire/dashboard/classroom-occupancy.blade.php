<div>
    <x-container-second-div>
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Ocupación de Aulas
                </h3>

                <!-- Botón Actualizar -->
                <button wire:click="refresh"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 text-sm rounded-md transition-colors bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50">
                    <svg class="w-4 h-4 mr-2" wire:loading.class="animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span wire:loading.remove wire:target="refresh">Actualizar</span>
                    <span wire:loading wire:target="refresh">Actualizando...</span>
                </button>
            </div>

            <!-- Filtros -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Filtro Edificio/Módulo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Edificio/Módulo
                    </label>
                    <select wire:model.live="filterModulo"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($modulos as $modulo)
                            <option value="{{ $modulo->id }}">{{ $modulo->code }} - {{ $modulo->address }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Día -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Día
                    </label>
                    <select wire:model.live="selectedDay"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        @foreach($dias as $key => $dia)
                            <option value="{{ $key }}">{{ $dia }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Leyenda con Filtros -->
            <div class="flex flex-wrap gap-4 text-sm">
                <!-- Filtro Disponible -->
                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 px-3 py-2 rounded-md transition-colors">
                    <input type="checkbox"
                           wire:model.live="showDisponible"
                           class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Disponible</span>
                </label>

                <!-- Filtro En Uso -->
                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 px-3 py-2 rounded-md transition-colors">
                    <input type="checkbox"
                           wire:model.live="showEnUso"
                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">En Uso</span>
                </label>

                <!-- Filtro Próxima -->
                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 px-3 py-2 rounded-md transition-colors">
                    <input type="checkbox"
                           wire:model.live="showProxima"
                           class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Próxima (< 30min)</span>
                </label>
            </div>
        </div>

        <!-- Grid de Aulas -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @forelse($classrooms as $classroom)
                @php
                    $occupancy = $this->getOccupancyStatus($classroom);
                @endphp

                <div wire:click="showClassroomDetails({{ $classroom->id }})"
                     class="p-4 rounded-lg border-2 cursor-pointer hover:shadow-lg transition-all {{ $occupancy['color'] }}">

                    <!-- Número de Aula -->
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        {{ $classroom->number }}
                    </div>

                    <!-- Capacidad -->
                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Capacidad: {{ $classroom->capacity }}
                    </div>

                    <!-- Estado Badge -->
                    <div class="mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                            {{ $occupancy['status'] === 'disponible' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                            {{ $occupancy['status'] === 'en_uso' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                            {{ $occupancy['status'] === 'proxima' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300' : '' }}">
                            {{ $occupancy['label'] }}
                        </span>
                    </div>

                    <!-- Información de la clase actual/próxima -->
                    @if($occupancy['assignment'])
                        <div class="text-xs space-y-1 pt-2 border-t border-gray-300 dark:border-gray-600">
                            <div class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">
                                {{ $occupancy['assignment']->userSubject->subject->name }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                {{ Str::limit($occupancy['assignment']->userSubject->user->name, 20) }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                {{ substr($occupancy['assignment']->daySchedule->schedule->start, 0, 5) }} -
                                {{ substr($occupancy['assignment']->daySchedule->schedule->end, 0, 5) }}
                            </div>
                        </div>
                    @else
                        <div class="text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-300 dark:border-gray-600">
                            Sin clases programadas
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="mt-2 text-sm">No hay aulas disponibles</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($classrooms->hasPages())
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Mostrando {{ $classrooms->firstItem() ?? 0 }} - {{ $classrooms->lastItem() ?? 0 }} de {{ $classrooms->total() }} aulas
                </div>

                <div class="flex gap-2">
                    @if($classrooms->onFirstPage())
                        <span class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 rounded-lg cursor-not-allowed">
                            ←
                        </span>
                    @else
                        <button wire:click="previousPage"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-800 dark:hover:to-blue-900 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-medium">
                            ←
                        </button>
                    @endif

                    <span class="px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 text-blue-900 dark:text-blue-200 rounded-lg font-semibold shadow-sm">
                        Página {{ $classrooms->currentPage() }} de {{ $classrooms->lastPage() }}
                    </span>

                    @if($classrooms->hasMorePages())
                        <button wire:click="nextPage"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-800 dark:hover:to-blue-900 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-medium">
                            →
                        </button>
                    @else
                        <span class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 rounded-lg cursor-not-allowed">
                            →
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </x-container-second-div>

    <!-- Modal de detalles del aula -->
    @if($showDetailsModal && $selectedClassroom)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeModal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full p-6" wire:click.stop>
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Aula {{ $selectedClassroom->number }} - Horario Completo
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Número</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $selectedClassroom->number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Capacidad</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $selectedClassroom->capacity }} personas</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $selectedClassroom->type }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Módulo/Edificio</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ $selectedClassroom->module->code }} - {{ $selectedClassroom->module->address }}
                            </p>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p>Para ver el horario completo del día, consulta el panel de horarios semanales.</p>
                        </div>
                    </div>

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
