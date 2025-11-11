@props(['disabled' => false])

<select
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm
                    [&>option]:bg-white dark:[&>option]:bg-gray-800
                    [&>option]:text-gray-900 dark:[&>option]:text-white
                    [&>option]:py-2'
    ]) }}
>
    {{ $slot }}
</select>
