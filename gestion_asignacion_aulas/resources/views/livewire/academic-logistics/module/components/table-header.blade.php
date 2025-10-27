<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4" /> <span>{{ __('Id') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4" /> <span>{{ __('Code') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.address class="w-4 h-4" /> <span>{{ __('Address') }}</span>
        </div>
    </x-table-header>


    <x-table-header>
        <x-input-label>{{ __('Status') }}</x-input-label>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4" /> <span>{{ __('Action') }}</span>
        </div>
    </x-table-header>
</div>
