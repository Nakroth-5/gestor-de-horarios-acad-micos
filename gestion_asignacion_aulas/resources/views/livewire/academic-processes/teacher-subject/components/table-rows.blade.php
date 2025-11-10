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
        <div class="flex items-center ml-4 px-0">
            <x-table.select>
                @php
                    $subjects = $item->subjects()->where('subjects.is_active', true)->get();
                @endphp

                @if($subjects->count() > 0)
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->code }}" title="{{ $subject->name }}">{{ $subject->code }}</option>
                        @endforeach
                @endif
            </x-table.select>
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
