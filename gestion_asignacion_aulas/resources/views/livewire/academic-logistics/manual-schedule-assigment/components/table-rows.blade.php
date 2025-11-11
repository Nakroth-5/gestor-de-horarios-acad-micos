<div>
    {{-- Sigla --}}
    <x-table.td data="{{ $item['subject_code'] }}"/>

    {{-- Grupo --}}
    <x-table.td data="{{ $item['subject_group'] }}"/>

    {{-- Materia --}}
    <x-table.td data="{{ $item['subject_name'] }}"/>

    {{-- Docente --}}
    <x-table.td data="{{ $item['teacher_name'] }}"/>

    {{-- Horarios con aulas (hasta 3 días) --}}
    @foreach($item['schedules']->take(3) as $schedule)
        {{-- Día --}}
        <x-table.td data="{{ $schedule->daySchedule->day->name }}"/>

        {{-- Hora y Aula juntos --}}
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between gap-2">
                <div class="flex flex-col">
                    <span class="font-medium">
                        {{ date('H:i', strtotime($schedule->daySchedule->schedule->start)) }} -
                        {{ date('H:i', strtotime($schedule->daySchedule->schedule->end)) }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Aula {{ $schedule->classroom->number }}
                    </span>
                </div>
                <div class="flex gap-1">
                    <button
                        wire:click="edit({{ $schedule->id }})"
                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400"
                        title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        wire:click="delete({{ $schedule->id }})"
                        wire:confirm="¿Eliminar?"
                        class="text-red-600 hover:text-red-900 dark:text-red-400"
                        title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </td>
    @endforeach

    @for($i = $item['schedules']->count(); $i < 3; $i++)
        <x-table.td data=""/>
        <x-table.td data=""/>
    @endfor
</div>
