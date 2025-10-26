@props([
    'active' => true
])

<td>
    <div class="flex items-center ml-4 grid-cols-2 gap-1">
        @if($active ?? true)
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                    Activo
                </span>
        @else
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                    Inactivo
                </span>
        @endif
    </div>
</td>
