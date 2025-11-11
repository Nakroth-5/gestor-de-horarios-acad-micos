<div>
    {{-- Sigla --}}
    <x-table.td data="{{ $item['subject_code'] }}"/>

    {{-- Grupo --}}
    <x-table.td data="{{ $item['subject_group'] }}"/>

    {{-- Materia --}}
    <x-table.td data="{{ $item['subject_name'] }}"/>

    {{-- Docente --}}
    <x-table.td data="{{ $item['teacher_name'] }}"/>

    @php
        $byDay = $item['schedules_by_day'] ?? null;
    @endphp

    @for($d = 0; $d < 6; $d++)
        @php
            $assignmentForDay = $byDay && array_key_exists($d, $byDay) ? $byDay[$d] : null;
        @endphp

        {{-- Celda del nombre del día (usa el header para mostrar el nombre); aquí mostramos '--' si no hay --}}
        <x-table.td :data="($assignmentForDay && $assignmentForDay->daySchedule && $assignmentForDay->daySchedule->day)
            ? $assignmentForDay->daySchedule->day->name : '--'"/>

        {{-- Celda de hora / aula --}}
        @if($assignmentForDay)
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex flex-col">
                        <span class="font-medium">
                            {{ date('H:i', strtotime($assignmentForDay->daySchedule->schedule->start)) }} -
                            {{ date('H:i', strtotime($assignmentForDay->daySchedule->schedule->end)) }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Aula {{ $assignmentForDay->classroom->number ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </td>
        @else
            <x-table.td data="--"/>
        @endif
    @endfor

    <td class="px-6 py-4 whitespace-nowrap text-sm">
        <div class="flex gap-1 justify-center">
            @if(isset($item['ids'][0]))
                <button
                    class="w-full bg-gradient-to-r
                   from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600
                   text-white font-semibold py-2 px-4 rounded-md tracking-wider
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl
                   hover:shadow-blue-600/25 dark:hover:shadow-blue-400/25
                   hover:from-blue-700 hover:to-blue-600 dark:hover:from-blue-400 dark:hover:to-blue-500"
                    wire:click="edit({{ $item['ids'][0] }})"
                    title="Editar">
                    <x-icons.edit/>
                </button>
                <button
                    wire:click="delete({{ $item['ids'][0] }})"
                    wire:confirm="¿Eliminar?"
                    class="w-full bg-gradient-to-r
                   from-red-600 to-red-700 dark:from-red-500 dark:to-red-600
                   text-white font-semibold py-2 px-4 rounded-md tracking-wider
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl
                   hover:shadow-red-600/25 dark:hover:shadow-red-400/25
                   hover:from-red-700 hover:to-red-600 dark:hover:from-red-400 dark:hover:to-red-500"
                    title="Eliminar">
                    <x-icons.delete/>
                </button>
            @endif
        </div>
    </td>
</div>
