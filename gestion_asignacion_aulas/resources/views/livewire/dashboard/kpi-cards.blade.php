<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card: Asistencias de hoy -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Asistencias de hoy
                </p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $asistenciasHoy }}
                </p>
                @if($tendenciaAsistencias['direccion'] !== 'neutral')
                    <div class="flex items-center mt-2 text-sm">
                        @if($tendenciaAsistencias['direccion'] === 'up')
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $tendenciaAsistencias['porcentaje'] }}%</span>
                        @else
                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $tendenciaAsistencias['porcentaje'] }}%</span>
                        @endif
                        <span class="text-gray-500 dark:text-gray-400 ml-1">vs ayer</span>
                    </div>
                @endif
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Retrasos -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Retrasos
                </p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $retrasosHoy }}
                </p>
                @if($tendenciaRetrasos['direccion'] !== 'neutral')
                    <div class="flex items-center mt-2 text-sm">
                        @if($tendenciaRetrasos['direccion'] === 'up')
                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $tendenciaRetrasos['porcentaje'] }}%</span>
                        @else
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $tendenciaRetrasos['porcentaje'] }}%</span>
                        @endif
                        <span class="text-gray-500 dark:text-gray-400 ml-1">vs ayer</span>
                    </div>
                @endif
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Inasistencias -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Inasistencias
                </p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $inasistenciasHoy }}
                </p>
                @if($tendenciaInasistencias['direccion'] !== 'neutral')
                    <div class="flex items-center mt-2 text-sm">
                        @if($tendenciaInasistencias['direccion'] === 'up')
                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $tendenciaInasistencias['porcentaje'] }}%</span>
                        @else
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $tendenciaInasistencias['porcentaje'] }}%</span>
                        @endif
                        <span class="text-gray-500 dark:text-gray-400 ml-1">vs ayer</span>
                    </div>
                @endif
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Sesiones programadas -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Sesiones programadas
                </p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $sesionesSemanales }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    esta semana
                </p>
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
