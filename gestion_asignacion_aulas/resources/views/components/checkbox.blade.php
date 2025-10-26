<input
    type="checkbox"
    {!! $attributes->merge([
        /* CAMBIO: Borde oscuro a blue-700 y color de acento/ring a blue */
        'class' => 'rounded dark:bg-gray-900 border-gray-300 dark:border-blue-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800'
    ]) !!}
>
