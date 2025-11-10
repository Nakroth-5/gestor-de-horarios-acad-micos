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

    {{-- 3 columnas para horarios con aulas --}}
    @for($i = 1; $i <= 3; $i++)
        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.timedate class="w-4 h-4" /> <span>{{ __('DIA') }} {{ $i }}</span>
            </div>
        </x-table-header>

        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.time class="w-4 h-4" /> <span>{{ __('HORA/AULA') }} {{ $i }}</span>
            </div>
        </x-table-header>
    @endfor
</div>
