<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-medium">{{ __('Welkom, :name!', ['name' => Auth::user()->name]) }}</p>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Welkom bij het Festival Travel System. Boek busreizen naar festivals door heel Europa.') }}
                    </p>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Puntensaldo') }}</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ Auth::user()->points_balance }}</p>
                    <a href="{{ route('profile.edit') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                        {{ __('Bekijk profiel') }} &rarr;
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Mijn boekingen') }}</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ Auth::user()->bookings()->count() }}</p>
                    <p class="mt-4 text-sm text-gray-500">
                        <a href="{{ route('festivals.index') }}" class="text-indigo-600 hover:text-indigo-800">
                            {{ __('Bekijk festivals') }} &rarr;
                        </a>
                    </p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('VIP-status') }}</h3>
                    @if (Auth::user()->isVip())
                        <p class="mt-2 text-lg font-semibold text-amber-600">{{ __('Actief') }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ __('Je hebt voorrang bij busplaatsing.') }}</p>
                    @else
                        <p class="mt-2 text-lg font-semibold text-gray-400">{{ __('Nog niet') }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ __('Verdien :points punten voor VIP-status.', ['points' => config('fts.vip_threshold')]) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
