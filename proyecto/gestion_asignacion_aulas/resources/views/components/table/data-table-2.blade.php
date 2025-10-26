@props([
    'items' => collect(),
    'tableHeader' => null,
    'tableRows' => null,
])

<div>
    {{-- Mensajes de Exito/Error --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <x-container-second-div>
        <div class="overflow-x-auto rounded-lg bg-orange-500">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                {{-- Encabezado de la Tabla --}}
                <tr class="bg-gray-800">
                    @if($tableHeader)
                        @include($tableHeader)
                    @endif
                </tr>
                </thead>
                <tbody>
                {{-- Filas de la Tabla --}}
                @if($items)
                    @forelse($items as $item)
                        <tr class="bg-gray-800 hover:bg-gray-900">
                            @if($tableRows)
                                @include($tableRows, compact('item'))
                            @endif
                        </tr>
                    @empty
                        <tr class="bg-gray-800">
                            <td colspan="100%" class="px-6 py-4 text-center text-gray-400">
                                No hay datos disponibles
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </x-container-second-div>
</div>
