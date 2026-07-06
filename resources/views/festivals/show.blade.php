<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $festival->name }}
            </h2>
            <a href="{{ route('festivals.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                &larr; {{ __('Terug naar overzicht') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Locatie') }}</dt>
                        <dd class="mt-1 text-gray-900">{{ $festival->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Datum') }}</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $festival->start_date->format('d-m-Y') }} – {{ $festival->end_date->format('d-m-Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Beschikbaarheid') }}</dt>
                        <dd class="mt-1 {{ $festival->isFull() ? 'text-red-600' : 'text-green-600' }}">
                            {{ $festival->availableSpots() }} / {{ $festival->max_capacity }} {{ __('plaatsen vrij') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Prijs') }}</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            &euro; {{ number_format($festival->ticket_price, 2, ',', '.') }}
                        </dd>
                    </div>
                </dl>

                @if ($festival->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Beschrijving') }}</h3>
                        <p class="mt-2 text-gray-700">{{ $festival->description }}</p>
                    </div>
                @endif

                <div class="mt-8 border-t border-gray-200 pt-6">
                    @if ($festival->hasBookingFrom(auth()->user()))
                        <p class="text-sm text-green-600 font-medium">{{ __('Je hebt dit festival al geboekt.') }}</p>
                    @elseif ($festival->isFull())
                        <p class="text-sm text-red-600 font-medium">{{ __('Dit festival is vol.') }}</p>
                    @else
                        <form method="POST" action="{{ route('festivals.book', $festival) }}">
                            @csrf
                            <p class="text-sm text-gray-600 mb-4">
                                {{ __('Na boeking ontvang je :points punten.', ['points' => config('fts.points_per_booking')]) }}
                                @if (auth()->user()->available_discount > 0)
                                    <span class="block mt-1 text-green-600">
                                        {{ __('Je hebt € :amount korting beschikbaar!', ['amount' => number_format(auth()->user()->available_discount, 2, ',', '.')]) }}
                                    </span>
                                @endif
                            </p>
                            <x-primary-button>
                                {{ __('Boek busreis') }}
                            </x-primary-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
