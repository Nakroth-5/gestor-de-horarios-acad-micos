<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-container-second-div>
            <div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-container-second-div>

        <x-container-second-div>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-container-second-div>
    </div>
</x-app-layout>
