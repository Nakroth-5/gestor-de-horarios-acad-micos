@props([
    'item' => null
])

<td>
    <div class="flex items-center ml-4 space-x-1">

        <button
            class="w-full bg-gradient-to-r
                   from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600
                   text-white font-semibold py-2 px-4 rounded-md tracking-wider
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl
                   hover:shadow-blue-600/25 dark:hover:shadow-blue-400/25
                   hover:from-blue-700 hover:to-blue-600 dark:hover:from-blue-400 dark:hover:to-blue-500"
            wire:click="edit({{ $item->id }})">
            <x-icons.edit/>
        </button>

        <button
            class="w-full bg-gradient-to-r
                   from-red-600 to-red-700 dark:from-red-500 dark:to-red-600
                   text-white font-semibold py-2 px-4 rounded-md tracking-wider
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl
                   hover:shadow-red-600/25 dark:hover:shadow-red-400/25
                   hover:from-red-700 hover:to-red-600 dark:hover:from-red-400 dark:hover:to-red-500"
            wire:click="delete({{ $item->id }})">
            <x-icons.delete/>
        </button>
    </div>
</td>
