<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4" /> <span>{{ __('SIGLA') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.number class="w-4 h-4" /> <span>{{ __('GR') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.classroom class="w-4 h-4" /> <span>{{ __('MATERIA') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.users class="w-4 h-4" /> <span>{{ __('DOCENTE') }}</span>
        </div>
    </x-table-header>

    @for($i = 1; $i <= 6; $i++)
        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.timedate class="w-4 h-4" /> <span>{{ __('CLASSROOM/DAY') }}</span>
            </div>
        </x-table-header>

        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.time class="w-4 h-4" /> <span>{{ __('HOUR') }}</span>
            </div>
        </x-table-header>
    @endfor

    <x-table-header>
        <x-icons.settings/> <span>{{ __('OPCIONES') }}</span>
    </x-table-header>
</div>
