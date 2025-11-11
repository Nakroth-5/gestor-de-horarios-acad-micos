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

    @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    @endphp
    {{-- 3 columnas para horarios con aulas --}}
    @for($i = 0; $i < 6; $i++)
        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.timedate class="w-4 h-4" /> <x-input-label for="day_{{ $i }}" :value="__($days[$i])"/>
            </div>
        </x-table-header>

        <x-table-header>
            <div class="flex items-center space-x-2">
                <x-icons.time class="w-4 h-4" /> <span>{{ __('HORA/AULA') }} {{ $i }}</span>
            </div>
        </x-table-header>
    @endfor

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4" /> <span>{{ __('Action') }}</span>
        </div>
    </x-table-header>
</div>
