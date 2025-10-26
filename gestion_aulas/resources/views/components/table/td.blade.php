@props([
   'data' => '',
])

<td class="px-6 py-2 whitespace-nowrap">
    <div class="flex items-center ml-4">
        <span class="text-sm text-slate-600 dark:text-slate-400">
            {{ $data }}
        </span>
        {{ $slot }}
    </div>
</td>
