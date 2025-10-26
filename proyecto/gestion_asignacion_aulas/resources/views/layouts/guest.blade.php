<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Académico') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20">

    <!-- Decorative background elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <a href="/" wire:navigate class="group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 flex items-center justify-center group-hover:scale-105">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </a>
            <h1 class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
                Sistema Académico
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Gestión de Horarios y Aulas
            </p>
        </div>

        <!-- Card de contenido -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>

        <!-- Links adicionales -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-600 dark:text-gray-400">
                &copy; {{ date('Y') }} Sistema de Gestión Académica
            </p>
        </div>
    </div>
</div>
</body>
</html>
