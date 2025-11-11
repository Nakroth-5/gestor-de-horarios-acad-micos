<div
    {{ $attributes->merge([
        'class' =>
            'p-6 sm:p-8 bg-white dark:bg-gray-900 shadow rounded-lg 
            sm:rounded-lg transform hover:scale-[1.01] 
            transition-transform duration-200 border-l-4 
            border-blue-600',
    ]) }}>
    {{ $slot }}
</div>
