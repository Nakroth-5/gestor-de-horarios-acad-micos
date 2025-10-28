<div>
    <x-table.data-table
        :items="$daySchedules"

        header="livewire.academic-logistics.schedule_block.components.header-schedule-block"
        table-header="livewire.academic-logistics.schedule_block.components.table-header"
        table-rows="livewire.academic-logistics.schedule_block.components.table-rows"
        modal="livewire.academic-logistics.schedule_block.modal-edit-store"
        :editing="$editing"
        :search="$search"
        :show="$show"/>
</div>
