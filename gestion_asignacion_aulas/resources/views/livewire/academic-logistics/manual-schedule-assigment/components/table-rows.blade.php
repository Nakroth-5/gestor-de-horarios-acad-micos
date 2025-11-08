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
        // Definir los días de la semana que quieres mostrar (en inglés como en tu BD)
        $diasSemana = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Organizar los horarios por día (guardando TODOS los horarios, no solo uno)
        $horariosPorDia = [];
        foreach($item['schedules'] as $schedule) {
            $dia = trim($schedule->daySchedule->day->name ?? '');
            if($dia !== '') {
                // Normalizar clave (usar el mismo texto que $diasSemana)
                if(!isset($horariosPorDia[$dia])) {
                    $horariosPorDia[$dia] = [];
                }
                $horariosPorDia[$dia][] = $schedule;
            }
        }

        // Obtener un id representativo para las acciones (editar/eliminar)
        $representativeId = $item['ids'][0] ?? null;
    @endphp

    @foreach($diasSemana as $dia)
        @if(isset($horariosPorDia[$dia]) && count($horariosPorDia[$dia]) > 0)
            {{-- Mostrar el primer horario del día --}}
            @php $schedule = $horariosPorDia[$dia][0]; @endphp

            {{-- Aula y día --}}
            <x-table.td data="{{ $schedule->classroom->number ?? '--' }} -- {{ $dia }}"/>

            {{-- Hora y Aula juntos --}}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex flex-col">
                    <span class="font-medium">
                        {{ isset($schedule->daySchedule->schedule->start) ? date('H:i', strtotime($schedule->daySchedule->schedule->start)) : '--' }} -
                        {{ isset($schedule->daySchedule->schedule->end) ? date('H:i', strtotime($schedule->daySchedule->schedule->end)) : '--' }}
                    </span>
                        @if(count($horariosPorDia[$dia]) > 1)
                            <span class="text-xs text-gray-500">
                            +{{ count($horariosPorDia[$dia]) - 1 }} más
                        </span>
                        @endif
                    </div>
                </div>
            </td>
        @else
            {{-- No hay horario para este día --}}
            <x-table.td data="--"/>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex flex-col">
                    <span class="font-medium text-gray-400">
                        --
                    </span>
                    </div>
                </div>
            </td>
        @endif
    @endforeach

    {{-- Opciones --}}
    <td class="flex gap-1">
        @if($representativeId)
            <x-primary-button
                wire:click="edit({{ $representativeId }})"
                title="Editar">
                <x-icons.edit/>
            </x-primary-button>
            <x-primary-button
                wire:click="delete({{ $representativeId }})"
                wire:confirm="Â¿Eliminar?"
                title="Eliminar">
                <x-icons.delete/>
            </x-primary-button>
        @else
            {{-- Si no hay id, mostrar botones deshabilitados o un placeholder --}}
            <button class="opacity-50 cursor-not-allowed px-3 py-1 rounded border text-sm">Editar</button>
            <button class="opacity-50 cursor-not-allowed px-3 py-1 rounded border text-sm">Eliminar</button>
        @endif
    </td>
</div>
