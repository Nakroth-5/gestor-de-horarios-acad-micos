<div>
    <x-table.data-table
        :items="$daySchedules"

        header="livewire.schedule_block.components.header-schedule-block"
        table-header="livewire.schedule_block.components.table-header"
        table-rows="livewire.schedule_block.components.table-rows"
        modal="livewire.schedule_block.modal-edit-store"
        :editing="$editing"
        :search="$search"
        :show="$show"/>
</div>
