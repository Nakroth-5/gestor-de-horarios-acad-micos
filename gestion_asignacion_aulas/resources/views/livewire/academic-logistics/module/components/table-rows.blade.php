<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->code }}"/>

    <x-table.td data="{{ $item->address ?? '' }}"/>

    <x-table.td-status :active="$item->is_active ?? true"/>

    <x-table.td-action :item="$item"/>
</div>
