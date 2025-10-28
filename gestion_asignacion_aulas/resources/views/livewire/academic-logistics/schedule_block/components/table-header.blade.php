<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4 text-yellow-100" /> <span>{{ __('Id') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.timedate class="w-4 h-4 text-yellow-600" /> <span>{{ __('Day') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.status class="w-4 h-4 text-green-500" /> <span>{{ __('Start Time') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.status class="w-4 h-4 text-blue-500" /> <span>{{ __('End Time') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4 text-red-800" /> <span>{{ __('Action') }}</span>
        </div>
    </x-table-header>
</div>
