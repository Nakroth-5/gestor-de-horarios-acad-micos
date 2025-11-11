<div>
    <x-table.data-table
        :items="$assignments"
        header="livewire.academic-logistics.manual-schedule-assigment.components.header-assignment"
        table-header="livewire.academic-logistics.manual-schedule-assigment.components.table-header"
        table-rows="livewire.academic-logistics.manual-schedule-assigment.components.table-rows"
        :editing="$editing"
        :search="$search"
        :show="$show"/>

    @include('livewire.academic-logistics.manual-schedule-assigment.modal-edit-store')

    {{-- Mensajes flash --}}
    @if (session()->has('assignment_message'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg">
        {{ session('assignment_message') }}
    </div>
    @endif
</div>
