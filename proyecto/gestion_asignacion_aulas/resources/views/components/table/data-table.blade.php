@props([
    'header' => null,
    'items' => collect(),
    'tableHeader' => null,
    'tableRows' => null,
    'modal' => null,
    'search' => '',
    'show' => false,
    'editing' => null,
    'relations' => null,
])

<div>
    {{-- Encabezado(Buscar, Crear) Items --}}
    @if($header)
        @include($header, ['search' => $search ?? ''])
    @endif

    {{-- Mensajes de Exito/Error --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-600 dark:text-red-300 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <x-container-second-div>
        <div class="overflow-x-auto rounded-lg bg-white dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                <tr class="bg-gray-100 dark:bg-gray-900">
                    @if($tableHeader)
                        @include($tableHeader)
                    @endif
                </tr>
                </thead>
                <tbody>
                {{-- Filas de la Tabla --}}
                @if($items)
                    @forelse($items as $item)
                        <tr class="group hover:bg-blue-50 dark:hover:bg-blue-950 transition-colors duration-150 relative">
                            @if($tableRows)
                                @include($tableRows, compact('item'))
                            @endif
                            <div class="absolute inset-y-0 left-0 w-1 bg-transparent group-hover:bg-blue-600 dark:group-hover:bg-blue-500 transition-colors duration-150"></div>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="px-6 py-4 text-center text-slate-600 dark:text-slate-400">
                                No hay datos disponibles
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>

        @if(method_exists($items, 'hasPages') && $items->hasPages())
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @endif
    </x-container-second-div>
    @if($modal)
        @include($modal, compact('show', 'editing'))
    @endif
</div>
