@props(['color'])

<div class="absolute bottom-0 left-0 w-full h-1 bg-gray-700/50">
    <div
        x-show="autoCerrar"
        class="h-full transition-all duration-75 shadow-lg <x-toast.color-classes :color="$color" type="progress" />"
        :style="'width: ' + progreso + '%'">
    </div>
</div>
