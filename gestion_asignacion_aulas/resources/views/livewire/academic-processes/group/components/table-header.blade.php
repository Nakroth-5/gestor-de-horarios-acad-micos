<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4 text-yellow-600" /> <span>{{ __('Id') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
           <span>{{ __('Name') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <x-input-label>{{ __('Status') }}</x-input-label>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.subject class="w-4 h-4 text-blue-500" /> <span>{{ __('Subject') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4 text-red-800" /> <span>{{ __('Action') }}</span>
        </div>
    </x-table-header>
</div>
