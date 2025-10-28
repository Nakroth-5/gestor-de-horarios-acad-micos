<div>
    <x-table.td data="{{ $item->id }}"/>

    <x-table.td data="{{ $item->day->name }}"/>

    <x-table.td data="{{ $item->schedule->start }}"/>

    <x-table.td data="{{ $item->schedule->end }}"/>

    <x-table.td-action :item="$item"/>
</div>
