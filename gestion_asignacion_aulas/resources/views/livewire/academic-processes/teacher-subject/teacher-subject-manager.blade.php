<div>
    <x-table.data-table
        :items="$teachers"

        header="livewire.academic-processes.teacher-subject.components.header-teacher-subject"
        table-header="livewire.academic-processes.teacher-subject.components.table-header"
        table-rows="livewire.academic-processes.teacher-subject.components.table-rows"
        modal="livewire.academic-processes.teacher-subject.modal-edit-store"
        :editing="$editing"
        :search="$search"
        :show="$show"/>
</div>
