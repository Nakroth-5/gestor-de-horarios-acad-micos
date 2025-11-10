<div>
    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.key class="w-4 h-4 text-yellow-100" /> <span>{{ __('ID') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('Nombre Completo') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
            <span>{{ __('Email') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c-.25.78.527 1.467 1.182 1.045l.949-.61 2.354 1.964.818-2.552c.25-.78-.527-1.467-1.182-1.045l-.95.61-2.353-1.964z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('Documento') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
            </svg>
            <span>{{ __('Materias Asignadas') }}</span>
        </div>
    </x-table-header>

    <x-table-header>
        <div class="flex items-center space-x-2">
            <x-icons.settings class="w-4 h-4 text-red-800" /> <span>{{ __('Acción') }}</span>
        </div>
    </x-table-header>
</div>
