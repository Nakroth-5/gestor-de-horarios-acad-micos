<div>
    <x-table.data-table
        :items="$users"
        header="livewire.security-access.user.components.header-user"
        table-header="livewire.security-access.user.components.table-header"
        table-rows="livewire.security-access.user.components.table-rows"
        modal="livewire.security-access.user.modal-edit-store"
        :editing="$editing"
        :relations="$allRoles"
        :search="$search"
        :show="$show"/>
</div>

