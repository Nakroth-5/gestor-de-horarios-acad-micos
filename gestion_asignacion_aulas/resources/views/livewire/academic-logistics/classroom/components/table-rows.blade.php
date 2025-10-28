<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->module->code }}"/>

    <x-table.td data="{{ $item->number ?? '' }}"/>

    <x-table.td data="{{ $item->type ?? '' }}"/>

    <x-table.td data="{{ $item->capacity ?? '' }}"/>

    <x-table.td-status :active="$item->is_active ?? true"/>

    <x-table.td-action :item="$item"/>
</div>
