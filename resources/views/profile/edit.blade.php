<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn profiel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'booking-confirmed')
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ __('Je boeking is bevestigd! Je hebt :points punten verdiend.', ['points' => config('fts.points_per_booking')]) }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Puntensaldo') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Verdien punten bij elke boeking en wissel ze in voor korting of VIP-toegang.') }}
                        </p>
                    </header>
                    <div class="flex items-center gap-4">
                        <span class="text-3xl font-bold text-indigo-600">{{ $user->points_balance }}</span>
                        <span class="text-sm text-gray-500">{{ __('punten') }}</span>
                        @if ($user->isVip())
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                VIP
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-4">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Reisgeschiedenis') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Overzicht van je geboekte festivalreizen.') }}
                    </p>
                </header>

                @if ($user->bookings->isEmpty())
                    <p class="text-sm text-gray-500">{{ __('Je hebt nog geen reizen geboekt.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">{{ __('Festival') }}</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">{{ __('Datum') }}</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">{{ __('Prijs') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($user->bookings as $booking)
                                    <tr>
                                        <td class="px-4 py-2">{{ $booking->festival->name }}</td>
                                        <td class="px-4 py-2">{{ $booking->booked_at->format('d-m-Y') }}</td>
                                        <td class="px-4 py-2 capitalize">{{ $booking->status }}</td>
                                        <td class="px-4 py-2 text-right">&euro; {{ number_format($booking->price, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
