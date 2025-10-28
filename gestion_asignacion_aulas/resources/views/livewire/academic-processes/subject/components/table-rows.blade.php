<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->code }}"/>

    <x-table.td data=" {{ $item->name }}"/>

    <x-table.td data="{{ $item->credits }}"/>

    <x-table.td-status :active="$item->status ?? true"/>

    <x-table.td-action :item="$item"/>
</div>
