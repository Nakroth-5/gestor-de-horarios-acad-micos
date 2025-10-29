<x-guest-layout>
    <div class="w-full max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-xl overflow-hidden sm:rounded-lg border-l-4 border-indigo-500 dark:border-indigo-600">

        <div class="flex flex-col items-center">
            <div class="mb-4 z-50">
                <img src="{{ asset('escudoFicct.png') }}" alt="Logo" class="w-20 h-28">
            </div>


            <h2 class="mt-2 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                {{ __('Inicia sesión en tu cuenta') }}
            </h2>
        </div>

        <x-auth-session-status class="mt-6 mb-4" :status="session('status')"/>

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <x-input-label for="code" :value="__('Codigo')"/>
                <x-text-input id="code" class="block mt-1 w-full" type="number" name="code" :value="old('code')"
                              required autofocus autocomplete="username"/>
                <x-input-error :messages="$errors->get('code')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')"/>
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="current-password"/>
                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a class="underline font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
