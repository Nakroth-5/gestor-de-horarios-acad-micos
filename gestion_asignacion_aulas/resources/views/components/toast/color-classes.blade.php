@props(['color', 'type' => 'background'])

@php
$classes = [
    'background' => [
        'green' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-emerald-900/50 border-emerald-700 shadow-emerald-500/20',
        'red' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-red-900/50 border-red-700 shadow-red-500/20',
        'blue' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-blue-900/50 border-blue-700 shadow-blue-500/20',
        'yellow' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-yellow-900/50 border-yellow-700 shadow-yellow-500/20',
        'purple' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-purple-900/50 border-purple-700 shadow-purple-500/20',
        'orange' => 'bg-gradient-to-r from-gray-900 via-gray-900 to-orange-900/50 border-orange-700 shadow-orange-500/20',
    ],
    'button' => [
        'green' => 'bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 hover:from-emerald-700 hover:via-emerald-600 hover:to-emerald-500 text-white shadow-emerald-600/50',
        'red' => 'bg-gradient-to-r from-red-800 via-red-700 to-red-600 hover:from-red-700 hover:via-red-600 hover:to-red-500 text-white shadow-red-600/50',
        'blue' => 'bg-gradient-to-r from-blue-800 via-blue-700 to-blue-600 hover:from-blue-700 hover:via-blue-600 hover:to-blue-500 text-white shadow-blue-600/50',
        'yellow' => 'bg-gradient-to-r from-yellow-800 via-yellow-700 to-yellow-600 hover:from-yellow-700 hover:via-yellow-600 hover:to-yellow-500 text-white shadow-yellow-600/50',
        'purple' => 'bg-gradient-to-r from-purple-800 via-purple-700 to-purple-600 hover:from-purple-700 hover:via-purple-600 hover:to-purple-500 text-white shadow-purple-600/50',
        'orange' => 'bg-gradient-to-r from-orange-800 via-orange-700 to-orange-600 hover:from-orange-700 hover:via-orange-600 hover:to-orange-500 text-white shadow-orange-600/50',
    ],
    'progress' => [
        'green' => 'bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 shadow-emerald-700/50',
        'red' => 'bg-gradient-to-r from-red-800 via-red-700 to-red-600 shadow-red-700/50',
        'blue' => 'bg-gradient-to-r from-blue-800 via-blue-700 to-blue-600 shadow-blue-700/50',
        'yellow' => 'bg-gradient-to-r from-yellow-800 via-yellow-700 to-yellow-600 shadow-yellow-700/50',
        'purple' => 'bg-gradient-to-r from-purple-800 via-purple-700 to-purple-600 shadow-purple-700/50',
        'orange' => 'bg-gradient-to-r from-orange-800 via-orange-700 to-orange-600 shadow-orange-700/50',
    ],
    'icon' => [
        'green' => 'bg-emerald-500/20 shadow-lg shadow-emerald-500/30',
        'red' => 'bg-red-500/20 shadow-lg shadow-red-500/30',
        'blue' => 'bg-blue-500/20 shadow-lg shadow-blue-500/30',
        'yellow' => 'bg-yellow-500/20 shadow-lg shadow-yellow-500/30',
        'purple' => 'bg-purple-500/20 shadow-lg shadow-purple-500/30',
        'orange' => 'bg-orange-500/20 shadow-lg shadow-orange-500/30',
    ],
    'icon-text' => [
        'green' => 'text-emerald-400',
        'red' => 'text-red-400',
        'blue' => 'text-blue-400',
        'yellow' => 'text-yellow-400',
        'purple' => 'text-purple-400',
        'orange' => 'text-orange-400',
    ],
];

// Obtener la clase con fallback a blue
$result = $classes[$type][$color] ?? $classes[$type]['blue'] ?? '';

echo $result;
@endphp
