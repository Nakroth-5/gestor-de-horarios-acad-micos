<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td>
        <div class="font-medium text-gray-900 dark:text-white">
            {{ $item->name }} {{ $item->last_name }}
        </div>
    </x-table.td>

    <x-table.td data="{{ $item->email }}"/>

    <x-table.td data="{{ $item->document_number }}"/>

    <td>
        <div class="px-4 py-2">
            @php
                $userSubjects = \App\Models\UserSubject::where('user_id', $item->id)
                    ->with(['subject', 'universityCareer'])
                    ->get();
            @endphp

            @if($userSubjects->count() > 0)
                <div class="space-y-1">
                    @foreach($userSubjects as $us)
                        @if($us->subject && $us->subject->is_active)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded font-mono">
                                    {{ $us->subject->code }}
                                </span>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $us->subject->name }}
                                </span>
                                @if($us->universityCareer)
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                                        {{ $us->universityCareer->code }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded text-xs italic">
                                        Sin carrera
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <span class="text-xs text-gray-500 dark:text-gray-400 italic">Sin materias asignadas</span>
            @endif
        </div>
    </td>

    <!-- Acción: Solo editar -->
    <x-table.td>
        <div class="flex items-center justify-center gap-2">
            <button
                wire:click="edit({{ $item->id }})"
                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                title="Asignar materias"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Asignar
            </button>
        </div>
    </x-table.td>
</div>
