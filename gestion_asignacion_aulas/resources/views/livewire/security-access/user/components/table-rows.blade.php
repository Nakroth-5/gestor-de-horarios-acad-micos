<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->code }}"/>

    <x-table.td data=" {{ $item->name }} {{ $item->last_name }}"/>

    <x-table.td data="{{ $item->phone }}"/>

    <x-table.td data="{{ $item->email }}"/>

    <x-table.td-status :active="$item->status ?? true"/>

    <td>
        <div class="flex items-center ml-4 px-0">
            <x-table.select>
                @php
                    $roles = $item->roles()->where('is_active', true)->get();
                @endphp
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </x-table.select>
        </div>
    </td>
    <x-table.td-action :item="$item"/>
</div>
