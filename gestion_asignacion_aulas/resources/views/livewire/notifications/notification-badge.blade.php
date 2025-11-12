<a href="{{ route('notifications.index') }}"
    wire:poll.60s
   class="flex items-center justify-between p-3 text-gray-700 rounded-lg dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group transition {{ request()->routeIs('notifications.*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
    <div class="flex items-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span class="ms-3">Notificaciones</span>
    </div>
    @if($unreadCount > 0)
        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-600 rounded-full animate-pulse">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</a>
