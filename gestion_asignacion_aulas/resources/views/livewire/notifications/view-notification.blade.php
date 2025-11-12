<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('notifications.index') }}"
               class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle de Notificación
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-container-second-div>
                {{-- Header de la notificación --}}
                <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-5xl">{{ $notification->emoji }}</span>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $notification->title }}
                            </h1>
                            <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                <span>·</span>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>

                            </div>
                        </div>
                    </div>

                    {{-- Badge de prioridad --}}
                    <div>
                        @if($notification->priority === 'urgent')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                🔴 Urgente
                            </span>
                        @elseif($notification->priority === 'important')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                🟡 Importante
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">
                                ⚪ Normal
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Mensaje completo --}}
                <div class="prose dark:prose-invert max-w-none mb-6">
                    <div class="text-gray-800 dark:text-gray-200 text-lg leading-relaxed whitespace-pre-line">
                        {{ $notification->message }}
                    </div>
                </div>



                {{-- Estado de lectura --}}
                <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>
                                @if($notification->read_at)
                                    Leída el {{ $notification->read_at->format('d/m/Y H:i') }}
                                @else
                                    Sin leer
                                @endif
                            </span>
                        </div>
                        <span class="text-gray-500 dark:text-gray-400">
                            ID: #{{ $notification->id }}
                        </span>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="mt-8 flex flex-wrap gap-3 justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-3">
                        <a href="{{ route('notifications.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver a notificaciones
                        </a>
                    </div>

                    <button wire:click.stop="deleteNotification({{ $notification->id }})"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                            title="Eliminar notificación">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar notificación
                    </button>

                </div>
            </x-container-second-div>
        </div>
    </div>
</div>
