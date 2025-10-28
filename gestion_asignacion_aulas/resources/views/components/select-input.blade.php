@props(['disabled' => false])

<select 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => 'mt-2 block w-full 
                    bg-gray-800 border border-gray-700 text-white 
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 focus:ring-offset-gray-800
                    rounded-md shadow-sm 
                    cursor-pointer
                    transition-colors duration-150
                    hover:bg-gray-700
                    disabled:opacity-50 disabled:cursor-not-allowed
                    [&>option]:bg-gray-800 [&>option]:text-white [&>option]:py-2'
    ]) }}
>
    {{ $slot }}
</select>