{{-- Nav con gradiente y efectos --}}
<nav x-data="{ open: false }" class="bg-gradient-to-r from-white via-blue-50 to-sky-50 dark:from-gray-900 dark:via-gray-900 dark:to-blue-950 border-b-2 border-blue-200 dark:border-blue-800 shadow-lg backdrop-blur-md bg-opacity-95 dark:bg-opacity-95 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between sm:justify-end items-center h-16">

            <button
                data-drawer-target="sidebar"
                data-drawer-toggle="sidebar"
                aria-controls="sidebar"
                type="button"
                class="inline-flex items-center p-2 text-gray-500 rounded-lg sm:hidden hover:bg-blue-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:text-gray-400 dark:focus:ring-blue-600 transition-all duration-200 hover:scale-110 active:scale-95">
                <span class="sr-only">Abrir menú</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                          d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>

            <div class="flex items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        {{-- Trigger con efectos hover --}}
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-sky-50 dark:hover:from-gray-800 dark:hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all ease-in-out duration-200 transform hover:scale-105 active:scale-95 shadow-sm hover:shadow-md">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')"
                                         class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-sky-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 transform hover:translate-x-1">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                             class="hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 transform hover:translate-x-1">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</nav>
