<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data=" {{ $item->name }}"/>

    <x-table.td-status :active="$item->is_active ?? true"/>

    <td>
        <div class="flex items-center ml-4 px-0">
            <x-table.select>
                @php
                    $subjects = $item->subjects()->where('is_active', true)->get();
                @endphp
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->code }}</option>
                @endforeach
            </x-table.select>
        </div>
    </td>

    <x-table.td-action :item="$item"/>
</div>
