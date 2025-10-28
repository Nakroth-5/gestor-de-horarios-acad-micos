<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4 text-yellow-600" /> <span>{{ __('Id') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4 text-green-400" /> <span>{{ __('Code') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.subject class="w-4 h-4 text-blue-500" /> <span>{{ __('Subject') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.credit_card class="w-4 h-4 text-green-800" /> <span>{{ __('Credits') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.status class="w-4 h-4 text-green-500" /> <span>{{ __('Status') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4 text-red-800" /> <span>{{ __('Action') }}</span>
        </div>
    </x-table-header>
</div>
