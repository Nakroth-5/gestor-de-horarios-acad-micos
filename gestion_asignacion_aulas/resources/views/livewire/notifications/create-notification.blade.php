<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crear Nueva Notificación
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-container-second-div>
                <form wire:submit.prevent="sendNotification" class="space-y-6">

                    {{-- Usuario destinatario --}}
                    <div>
                        <x-input-label for="user_id" value="Usuario Destinatario" />
                        <select id="user_id" wire:model.live="user_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 rounded-md shadow-sm">
                            <option value="">Seleccione un usuario...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (auto-completado) --}}
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" value="{{ $email }}" class="mt-1 block w-full" readonly />
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Se completa automáticamente al seleccionar el usuario
                        </p>
                    </div>

                    {{-- Asunto --}}
                    <div>
                        <x-input-label for="subject" value="Asunto" />
                        <x-text-input id="subject" type="text" wire:model="subject" class="mt-1 block w-full"
                                      placeholder="Ej: Actualización importante del sistema" />
                        @error('subject')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mensaje --}}
                    <div>
                        <x-input-label for="message" value="Mensaje" />
                        <textarea id="message" wire:model="message" rows="8"
                                  class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 rounded-md shadow-sm"
                                  placeholder="Escriba el contenido completo de la notificación. Puede incluir saltos de línea y párrafos..."></textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            💡 Consejo: Use saltos de línea para separar párrafos y hacer el mensaje más legible
                        </p>
                    </div>

                    {{-- Prioridad --}}
                    <div>
                        <x-input-label for="priority" value="Prioridad" />
                        <div class="mt-2 grid grid-cols-3 gap-3">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="radio" wire:model="priority" value="info" class="peer sr-only" />
                                <div class="w-full px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-700 rounded-lg transition-all
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                            hover:border-blue-400 dark:hover:border-blue-600">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-2xl">ℹ️</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Informativa</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center cursor-pointer">
                                <input type="radio" wire:model="priority" value="important" class="peer sr-only" />
                                <div class="w-full px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-700 rounded-lg transition-all
                                            peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/30
                                            hover:border-yellow-400 dark:hover:border-yellow-600">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-2xl">⚠️</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Importante</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center cursor-pointer">
                                <input type="radio" wire:model="priority" value="urgent" class="peer sr-only" />
                                <div class="w-full px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-700 rounded-lg transition-all
                                            peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/30
                                            hover:border-red-400 dark:hover:border-red-600">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-2xl">🚨</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Urgente</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vista previa --}}
                    @if($user_id && $subject && $message)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Vista Previa
                            </h4>
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl">
                                        @if($priority === 'urgent') 🚨
                                        @elseif($priority === 'important') ⚠️
                                        @else 💬
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <h5 class="font-bold text-gray-900 dark:text-white mb-2">{{ $subject }}</h5>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="cancel"
                                class="px-6 py-2.5 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                                wire:loading.attr="disabled">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span wire:loading.remove>Enviar Notificación</span>
                            <span wire:loading>Enviar Notificación</span>
                        </button>
                    </div>

                </form>
            </x-container-second-div>
        </div>
    </div>
</div>
