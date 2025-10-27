<div>
    <x-table.td data="{{ $item->user->name }}"/>

    <x-table.td data="{{ $item->action }}"/>

    <x-table.td data="{{ $item->affected_model ?? 'N/A' }}"/>

    <td class="px-6 py-2 whitespace-nowrap">
        <div class="flex flex-col ml-4 text-sm text-gray-700">
            @php
                $changes = $item->changes['after'] ?? [];
            @endphp

            @if(empty($changes))
                <x-input-label value="{{ 'Ningún cambio' }}"/>
            @else
                @foreach($changes as $field => $newValue)
                    @php
                        $oldValue = $item->changes['before'][$field] ?? '—';

                        // Formatear fechas
                        if ($field === 'updated_at' || $field === 'created_at') {
                            $newValue = \Carbon\Carbon::parse($newValue)->format('d/m/Y H:i:s');
                            $oldValue = $oldValue !== '—'
                                ? \Carbon\Carbon::parse($oldValue)->format('d/m/Y H:i:s')
                                : '—';
                        }

                        if (in_array($field, ['active', 'status', 'is_active', 'enabled'])) {
                            $newValue = $newValue == 1 ? 'Activo' : 'Inactivo';
                            $oldValue = $oldValue == 1 ? 'Activo' : ($oldValue === '—' ? '—' : 'Inactivo');
                        }

                        $fieldName = match($field) {
                            'is_active' => 'Activo',
                            'updated_at' => 'Actualizado',
                            'created_at' => 'Creado',
                            default => ucfirst(str_replace('_', ' ', $field))
                        };
                    @endphp
                    <div>
                        <x-input-label>
                            <strong>{{ $fieldName }}</strong>:
                            {{ $oldValue }} → {{ $newValue }}
                        </x-input-label>
                    </div>
                @endforeach
            @endif
        </div>
    </td>

    <x-table.td data="{{ $item->ip_address }}"/>

    <x-table.td data="{{ $item->updated_at }}"/>
</div>
