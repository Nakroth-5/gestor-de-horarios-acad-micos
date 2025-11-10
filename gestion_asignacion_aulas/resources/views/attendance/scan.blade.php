<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Marcar Asistencia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        @if($success)
                            {{-- Éxito --}}
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
                                    <svg class="h-10 w-10 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $message }}
                                </h2>

                                @if($attendanceData)
                                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detalles de la asistencia</h3>

                                        <dl class="space-y-3">
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Materia:</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $attendanceData['subject'] }}</dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grupo:</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $attendanceData['group'] }}</dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Aula:</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $attendanceData['classroom'] }}</dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha:</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($attendanceData['scan_time'])->format('d/m/Y') }}
                                                </dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hora de registro:</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($attendanceData['scan_time'])->format('H:i:s') }}
                                                </dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado:</dt>
                                                <dd>
                                                    @if($attendanceData['status'] === 'on_time')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            A tiempo
                                                        </span>
                                                    @elseif($attendanceData['status'] === 'late')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            Tarde
                                                        </span>
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                @endif

                                <div class="mt-8">
                                    <a href="{{ route('dashboard') }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Volver al inicio
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Error --}}
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                                    <svg class="h-10 w-10 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    Error al marcar asistencia
                                </h2>

                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    {{ $message }}
                                </p>

                                @if($assignment)
                                    <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <span class="font-semibold">Materia:</span> {{ $assignment->userSubject->subject->name }}<br>
                                            <span class="font-semibold">Grupo:</span> {{ $assignment->group->name }}
                                        </p>
                                    </div>
                                @endif

                                <div class="mt-8 space-x-4">
                                    <button onclick="window.history.back()"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Volver
                                    </button>
                                    <a href="{{ route('dashboard') }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Ir al inicio
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Si tienes problemas para marcar tu asistencia, contacta con tu docente o el administrador del sistema.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
