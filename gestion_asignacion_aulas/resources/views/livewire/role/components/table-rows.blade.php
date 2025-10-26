<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->name }}"/>

    <x-table.td data=" {{ $item->description }}"/>

    <x-table.td data="{{ $item->level }}"/>

    <x-table.td-status :active="$item->is_active ?? true"/>

    <td>
        <div class="flex items-center ml-4 px-0">
            <x-table.select>
                @php
                    $permissions = $item->permissions()->where('is_active', true)->get();
                @endphp
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                @endforeach
            </x-table.select>
        </div>
    </td>
    <x-table.td-action :item="$item"/>
</div>
