<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mi Horario y Asignaciones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Selector de Periodo y Modo de Vista -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Periodo Académico:</label>
                        <select wire:model.live="selectedPeriod"
                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600">
                            @foreach ($academicPeriods as $period)
                                <option value="{{ $period->id }}">{{ $period->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button wire:click="changeViewMode('schedule')"
                            class="px-4 py-2 rounded-md {{ $viewMode === 'schedule' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Horario
                        </button>
                        <button wire:click="changeViewMode('subjects')"
                            class="px-4 py-2 rounded-md {{ $viewMode === 'subjects' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Materias
                        </button>
                        <button wire:click="changeViewMode('summary')"
                            class="px-4 py-2 rounded-md {{ $viewMode === 'summary' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Resumen
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Materias</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $stats['total_subjects'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Grupos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $stats['total_groups'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Horas/Semana</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $stats['total_hours'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aulas</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $stats['classrooms'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido según modo de vista -->
            @if ($viewMode === 'schedule')
                <!-- Vista de Horario Estilo Calendario -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Horario Semanal</h3>


                    @if ($assignments->count() === 0)
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                            <p class="text-yellow-800 dark:text-yellow-200">No hay clases asignadas para este periodo
                                académico.</p>
                        </div>
                    @else
                        @php
                            // Obtener todos los horarios únicos ordenados (filtrar nulls)
                            $validAssignments = $assignments->filter(function ($a) {
                                return $a->daySchedule &&
                                    $a->daySchedule->schedule &&
                                    $a->daySchedule->schedule->start &&
                                    $a->daySchedule->schedule->end;
                            });

                            $allTimeSlots = $validAssignments
                                ->map(function ($a) {
                                    return [
                                        'start' => $a->daySchedule->schedule->start,
                                        'end' => $a->daySchedule->schedule->end,
                                        'label' =>
                                            date('H:i', strtotime($a->daySchedule->schedule->start)) .
                                            ' - ' .
                                            date('H:i', strtotime($a->daySchedule->schedule->end)),
                                    ];
                                })
                                ->unique('label')
                                ->sortBy('start')
                                ->values();

                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                            // Nombres en español para mostrar
                            $dayNames = [
                                'Monday' => 'Lunes',
                                'Tuesday' => 'Martes',
                                'Wednesday' => 'Miércoles',
                                'Thursday' => 'Jueves',
                                'Friday' => 'Viernes',
                                'Saturday' => 'Sábado',
                            ];

                            // Colores para cada materia (consistentes)
                            $colors = [
                                'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 border-blue-300 dark:border-blue-700',
                                'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border-green-300 dark:border-green-700',
                                'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 border-purple-300 dark:border-purple-700',
                                'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 border-yellow-300 dark:border-yellow-700',
                                'bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200 border-pink-300 dark:border-pink-700',
                                'bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 border-indigo-300 dark:border-indigo-700',
                                'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border-red-300 dark:border-red-700',
                                'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 border-orange-300 dark:border-orange-700',
                                'bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-200 border-teal-300 dark:border-teal-700',
                                'bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 border-cyan-300 dark:border-cyan-700',
                            ];

                            // Asignar color a cada materia
                            $subjectColors = [];
                            $colorIndex = 0;
                            foreach ($userSubjects as $item) {
                                if (!isset($subjectColors[$item['subject']->id])) {
                                    $subjectColors[$item['subject']->id] = $colors[$colorIndex % count($colors)];
                                    $colorIndex++;
                                }
                            }
                        @endphp

                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-900">
                                        <th
                                            class="border border-gray-300 dark:border-gray-700 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 w-32">
                                            HORARIO
                                        </th>
                                        @foreach ($days as $day)
                                            <th
                                                class="border border-gray-300 dark:border-gray-700 px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                {{ $dayNames[$day] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allTimeSlots as $timeSlot)
                                        <tr>
                                            <td
                                                class="border border-gray-300 dark:border-gray-700 px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800">
                                                {{ $timeSlot['label'] }}
                                            </td>
                                            @foreach ($days as $day)
                                                @php
                                                    $dayAssignments = isset($scheduleByDay[$day])
                                                        ? $scheduleByDay[$day]->filter(function ($a) use ($timeSlot) {
                                                            if (!$a->daySchedule || !$a->daySchedule->schedule) {
                                                                return false;
                                                            }
                                                            $scheduleStart = date(
                                                                'H:i',
                                                                strtotime($a->daySchedule->schedule->start),
                                                            );
                                                            $slotStart = date('H:i', strtotime($timeSlot['start']));
                                                            return $scheduleStart === $slotStart;
                                                        })
                                                        : collect();
                                                    $assignment = $dayAssignments->first();
                                                @endphp
                                                <td class="border border-gray-300 dark:border-gray-700 p-1 align-top">
                                                    @if ($assignment)
                                                        <div
                                                            class="p-2 rounded border-l-4 {{ $subjectColors[$assignment->subject->id] ?? $colors[0] }} text-xs">
                                                            <div class="font-bold">{{ $assignment->subject->code }} -
                                                                {{ $assignment->group->name }}</div>
                                                            <div class="mt-1">{{ $assignment->subject->name }}</div>
                                                            <div class="mt-1 text-xs opacity-75">
                                                                Aula: {{ $assignment->classroom->name }}
                                                            </div>
                                                            @if ($assignment->userSubject && $assignment->userSubject->universityCareer)
                                                                <div class="mt-1 text-xs font-semibold">
                                                                    {{ $assignment->userSubject->universityCareer->code }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @elseif($viewMode === 'subjects')
                <!-- Vista de Materias -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Mis Materias y Grupos</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($userSubjects as $item)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $item['subject']->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Grupo:
                                            {{ $item['group']->name }}</p>
                                        @if ($item['career'])
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Carrera:</span>
                                                <span
                                                    class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs">
                                                    {{ $item['career']->code }}
                                                </span>
                                                {{ $item['career']->name }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-500 italic">Sin carrera
                                                asignada</p>
                                        @endif
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Periodo:
                                            {{ $item['group']->academicManagement->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="ml-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                            Activo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($userSubjects->count() === 0)
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay materias asignadas para
                            este periodo.</p>
                    @endif
                </div>
            @else
                <!-- Vista de Resumen -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Resumen de Asignaciones
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Materia</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Carrera</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Grupo</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Horas/Semana</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Aulas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($userSubjects as $item)
                                    @php
                                        // Filtrar asignaciones por materia Y grupo específico
                                        $subjectAssignments = $assignments->where('subject_id', $item['subject']->id)
                                                                          ->where('group_id', $item['group']->id);
                                        $hoursPerWeek = $subjectAssignments->count();
                                        $classroomsList = $subjectAssignments
                                            ->pluck('classroom.name')
                                            ->unique()
                                            ->implode(', ');
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $item['subject']->name }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($item['career'])
                                                <span
                                                    class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs">
                                                    {{ $item['career']->code }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-gray-500 dark:text-gray-500 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $item['group']->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $hoursPerWeek }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $classroomsList ?: 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
