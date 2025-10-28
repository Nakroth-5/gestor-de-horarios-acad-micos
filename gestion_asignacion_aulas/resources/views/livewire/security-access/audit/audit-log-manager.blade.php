<div>
    <x-table.data-table
        :items="$auditLogs"
        header="livewire.security-access.audit.components.header-audit"
        table-header="livewire.security-access.audit.components.table-header"
        table-rows="livewire.security-access.audit.components.table-rows"
        :search="$search"/>
</div>
