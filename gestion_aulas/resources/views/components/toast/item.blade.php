@props(['toast', 'closeMethod', 'ignoreMethod'])

<div
    wire:key="toast-{{ $toast['id'] }}"
    x-data="{
        toastId: '{{ $toast['id'] }}',
        show: false,
        autoCerrar: {{ $toast['autoCierre'] ? 'true' : 'false' }},
        duracion: {{ $toast['duracion'] ?? 5 }},
        progreso: 100,
        timerInterval: null,
        inicializado: false
    }"
    x-init="
        if (!inicializado) {
            inicializado = true;
            show = true;
            if (autoCerrar) {
                let duracionMs = duracion;
                let intervalo = 50;
                let pasos = duracionMs / intervalo;
                let decrementoPorPaso = 100 / pasos;

                timerInterval = setInterval(() => {
                    progreso -= decrementoPorPaso;
                    if (progreso <= 0) {
                        clearInterval(timerInterval);
                        show = false;
                        setTimeout(() => {
                            @this.{{ $closeMethod }}(toastId);
                        }, 200);
                    }
                }, intervalo);
            }
        }
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-24 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
    x-transition:leave-end="opacity-0 translate-x-full scale-95"
    class="rounded-xl overflow-hidden shadow-2xl relative backdrop-blur-sm border-l-4 <x-toast.color-classes :color="$toast['color']" type="background" />">

    <div class="flex justify-between">
        <!-- Contenido -->
        <div class="flex items-start gap-3 p-3 flex-1">
            <!-- Icono -->
            <x-toast.icon :color="$toast['color']" />

            <!-- Texto -->
            <div class="flex-1 min-w-0">
                <p class="text-base font-semibold text-gray-100 leading-tight">{{ $toast['titulo'] }}</p>
                <p class="text-sm text-gray-300 mt-1 leading-snug">{{ $toast['descripcion'] }}</p>

                <!-- Botón "Entendido" (solo para alertas sin autoCierre) -->
                @if(!$toast['autoCierre'])
                    <button
                        @click="
                            show = false;
                            setTimeout(() => {
                                @this.{{ $closeMethod }}(toastId);
                            }, 200);
                        "
                        class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl <x-toast.color-classes :color="$toast['color']" type="button" />">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Entendido
                    </button>
                @endif
            </div>
        </div>

        <!-- Botón X: No volver a mostrar -->
        <button
            @click="
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
                show = false;
                setTimeout(() => {
                    @this.{{ $ignoreMethod }}(toastId);
                }, 200);
            "
            title="No volver a mostrar esta alerta"
            class="hover:bg-gray-700 rounded-lg border-none cursor-pointer p-2 transition-all duration-300 self-start hover:scale-110">
            <div class="w-4 h-4 text-gray-400 hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </div>
        </button>
    </div>

    <!-- Barra de progreso (solo si autoCierre) -->
    @if($toast['autoCierre'])
        <x-toast.progress-bar :color="$toast['color']" />
    @endif
</div>
